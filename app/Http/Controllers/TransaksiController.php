<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Outlet;
use App\Models\Paket;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use App\Models\LogActivity;
use Auth;
use Carbon\Carbon;
use Cart;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::select('id','nama')->get();

        $search = $request->search;
        $user = Auth::user();
        $outlet_id = $user->role != 'admin' ? $user->outlet_id : null;

        $transaksis = Transaksi::join('members','members.id','transaksis.member_id')
        ->join('users','users.id','transaksis.user_id')
        ->join('outlets','outlets.id','users.outlet_id')
        ->where('members.nama','like',"%{$search}%")
        ->when($outlet_id, function($query, $outlet_id){
            return $query->where('transaksis.outlet_id',$outlet_id);
        })
        ->select(
            'transaksis.id as id',
            'members.nama as nama',
            'members.tlp as tlp',
            'qty_total',
            'status',
            'dibayar',
            'tgl',
            'batas_waktu',
            'kode_invoice',
            'total_bayar',
            'outlets.nama as outlet'
        )
        ->orderBy('id','desc')
        ->paginate(25);

        $transaksis->map(function($row){
            $row->tgl = date('d/m/Y H:i:s',strtotime($row->tgl));
            $row->batas_waktu = date('d/m/Y H:i:s',strtotime($row->batas_waktu));
            $row->status = ucwords($row->status);
            $row->dibayar = ucwords( str_replace('_',' ',$row->dibayar) );
            $row->dikembalikan = ucwords( str_replace('_',' ',$row->dikembalikan) );
            $row->total_bayar = number_format($row->total_bayar,0,',','.');
        });

        return view('transaksi.index',[
            'members'=>$members,
            'transaksis'=>$transaksis
        ]);
    }

    public function create(Request $request, Member $member)
    {
        LogActivity::add('Berhasil Membuat Transaksi');
        $user = Auth::user();
        $outlet = Outlet::find($user->outlet_id);
        $pakets = Paket::where('outlet_id',$outlet->id)
        ->select('id as value','nama_paket as option')->get();
        $items = Cart::session($member->id)->getContent();

        return view('transaksi.create',[
            'member'=>$member,
            'outlet'=>$outlet,
            'pakets'=>$pakets,
            'items'=>$items,
            'total'=>Cart::session($member->id)->getTotal(),
        ]);
    }

    public function add(Request $request, Member $member)
    {
        $request->validate([
            'paket'=>'required|exists:pakets,id',
            'quantity'=>'required|numeric',
            'keterangan'=>'nullable|max:200',
        ]);

        $paket = Paket::find($request->paket);

        Cart::session($member->id)->add(array(
            'id' => $paket->id,
            'name' => $paket->nama_paket,
            'price' => $paket->harga,
            'quantity' =>$request->quantity,
            'attributes' => [
                'harga_awal' =>$paket->harga,
                'keterangan' =>$request->keterangan,
                'diskon' =>$paket->diskon,
            ]
         ));

         return back();
    }

    public function delete(Member $member, Paket $paket)
    {
        Cart::session($member->id)->remove($paket->id);
        return back();
    }

    public function clear(Member $member)
    {
        Cart::session($member->id)->clear();
        return back();
    }

    public function store(Request $request, Member $member)
    {
        $request->validate([
            'batas_waktu'=>'required|after:now',
            'diskon'=>'nullable|numeric',
            'biaya_tambahan'=>'nullable|numeric',
            'uang_tunai'=>'nullable|numeric'
        ]);

        if(Cart::session($member->id)->isEmpty()){
            return back()->withErrors([
                'paket'=>'Paket tidak boleh kosong.'
            ]);
        }

        $subtotal = Cart::session($member->id)->getTotal();
        $diskon = $request->diskon ;
        $biaya_tambahan = $request->biaya_tambahan ;
        $total = $subtotal - $diskon + $biaya_tambahan;
        $pajak = round($total * 10 / 100);
        $total_bayar = $total + $pajak;
        $uang_tunai = $request->uang_tunai ;
        $kembalian = $uang_tunai - $total_bayar ;

        if( $uang_tunai && ($kembalian < 0) ){
            return back()->withInput()->withErrors([
                'uang_tunai'=>'Uang tunai kurang dari total bayar'
            ]);
        }

        $user = Auth::user();
        $qty_total = Cart::session($member->id)->getTotalQuantity();

        $last_transaksi = Transaksi::orderBy('id','desc')->select('id')->first();
        $last_id = $last_transaksi ? $last_transaksi->id : 0;
        $id = sprintf("%04s",$last_id + 1);
        $invoice = date('Ymd').$id;

        $query_transaksi = [
            'outlet_id'=>$user->outlet_id,
            'member_id'=>$member->id,
            'user_id'=>$user->id,
            'kode_invoice'=>$invoice,
            'tgl'=>date('Y-m-d H:i:s'),
            'batas_waktu'=>date('Y-m-d H:i:s', strtotime($request->batas_waktu)),
            'tgl_bayar'=> $uang_tunai ? date('Y-m-d H:i:s') : null,
            'biaya_tambahan'=>$biaya_tambahan,
            'diskon'=>$diskon,
            'pajak'=>$pajak,
            'sub_total'=>$subtotal,
            'qty_total'=>$qty_total,
            'total_bayar'=>$total_bayar,
            'cash'=> $uang_tunai ? $uang_tunai: null,
            'kembalian'=> $uang_tunai ? $kembalian : null,
            'status'=>'baru',
            'dibayar'=> $uang_tunai ? 'dibayar' : 'belum_bayar',
            'dikembalikan'=> $uang_tunai ? 'dikembalikan' : null,
        ];

        $transaksi = Transaksi::create($query_transaksi);

        $items = Cart::session($member->id)->getContent();

        foreach ($items as $item) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'paket_id' => $item->id,
                'harga' => $item->attributes->harga_awal,
                'diskon_paket' => $item->attributes->diskon,
                'qty' => $item->quantity,
                'sub_total' => $item->price * $item->quantity,
                'keterangan' => $item->attributes->keterangan,
            ]);
        }
Cart::clear();
        return redirect()->route('transaksi.detail',['transaksi'=>$transaksi->id]);
        }

        public function detail(Transaksi $transaksi)
        {
            $user = User::find($transaksi->user_id);
            $member = Member::find($transaksi->member_id);
            $outlet = Outlet::find($transaksi->outlet_id);
            $items =
         TransaksiDetail::join('pakets','pakets.id','transaksi_details.paket_id')
            ->where('transaksi_id',$transaksi->id)
            ->select(
                'pakets.id as id',
                'nama_paket',
                'qty',
                'transaksi_details.harga as harga',
                'sub_total',
                'diskon_paket',
                'keterangan',
            )
            ->get();

            return view('transaksi.detail',[
                'items'=>$items,
                'member'=>$member,
                'user'=>$user,
                'outlet'=>$outlet,
                'transaksi'=>$transaksi
            ]);
        }

        public function update(Request $request, Transaksi $transaksi)
        {
            LogActivity::add('Berhasil Mengubah Pembayaran');
            $request->validate([
                'diskon'=>'nullable|numeric',
                'biaya_tambahan'=>'nullable|numeric',
                'uang_tunai'=>'nullable|numeric'
            ]);

            $subtotal = $transaksi->sub_total;
            $diskon = $request->diskon ;
            $biaya_tambahan = $request->biaya_tambahan ;
            $total = $subtotal - $diskon + $biaya_tambahan;
            $pajak = round($total * 10 / 100);
            $total_bayar = $total + $pajak;
            $uang_tunai = $request->uang_tunai ;
            $kembalian = $uang_tunai - $total_bayar ;

            if( $uang_tunai && ($kembalian < 0) ){
                return back()->withInput()->withErrors([
                    'uang_tunai'=>'Uang tunai kurang dari total bayar'
                ]);
        }

        $query_transaksi = [
            'tgl_bayar'=> $uang_tunai ? date('Y-m-d H:i:s') :null,
            'biaya_tambahan'=>$biaya_tambahan,
            'diskon'=>$diskon,
            'pajak'=>$pajak,
            'sub_total'=>$subtotal,
            'total_bayar'=>$total_bayar,
            'cash'=> $uang_tunai ? $uang_tunai: null,
            'kembalian'=> $uang_tunai ? $kembalian : null,
            'dibayar'=> $uang_tunai ? 'dibayar': 'belum_bayar',
        ];

        $transaksi->update($query_transaksi);

        return back()->with('message','success update');
    }

        public function updatecart(Request $request, Member $member, Paket $paket)
        {
            $type = $request->type;
            Cart::session($member->id)->update($paket->id,[
                'quantity' => $type == 'min' ? -1 : 1,
            ]);

            return back();
        }

    const ALLOWED_VALUES = ['baru', 'proses', 'selesai', 'diambil', 'batal'];

    public function status(Transaksi $transaksi, $status)
    {

        if (!in_array($status, self::ALLOWED_VALUES)) {
            return back();
        }

        if ($transaksi->status == 'diambil' || $transaksi->status == 'batal') {
            return back();
        }

        if ($transaksi->status == 'baru') {
            $nextStatus = 'proses' || 'batal' ;
        } elseif ($transaksi->status == 'proses') {
            $nextStatus = 'selesai';
        } elseif ($transaksi->status == 'selesai') {
            $nextStatus = 'diambil';
        } else {
            $nextStatus = null;
        }

        if ($nextStatus !== null && $status != $nextStatus) {
            return back();
        }

        $transaksi->update([
            'status'=>$status,
        ]);

        if ($status == 'selesai' || $status == 'diambil') {
            $transaksi->update([
                'tgl_' . $status => Carbon::now(),
            ]);
        }

        LogActivity::add('Berhasil Mengubah Status Transaksi');
        return back()->with('message','success update');
    }

    public function invoice(Transaksi $transaksi)
    {
        $user = User::find($transaksi->user_id);
        $member = Member::find($transaksi->member_id);
        $outlet = Outlet::find($transaksi->outlet_id);
        $items =
        TransaksiDetail::join('pakets','pakets.id','transaksi_details.paket_id')
        ->where('transaksi_id',$transaksi->id)
        ->select(
            'pakets.id as id',
            'nama_paket',
            'qty',
            'transaksi_details.harga as harga',
            'sub_total',
            'keterangan'
        )
        ->get();

        return view('transaksi.invoice',[
            'items'=>$items,
            'member'=>$member,
            'user'=>$user,
            'outlet'=>$outlet,
            'transaksi'=>$transaksi
        ]);
    }
}

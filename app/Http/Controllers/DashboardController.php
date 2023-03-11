<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Outlet;
use App\Models\Transaksi;
use App\Models\User;
use DB;
use Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $outlet_id = $auth->level == 'kasir' ? $auth->outlet_id : null;

        $user = User::select(DB::raw('count(id) as jumlah'))->first();
        $member = Member::select(DB::raw('count(id) as jumlah'))->first();
        $outlet = Outlet::select(DB::raw('count(id) as jumlah'))->first();

        $transaksi = Transaksi::where('dibayar', 'belum_bayar')
        ->select(DB::raw('count(id) as jumlah'))->first();

        $charts = Transaksi::where('dibayar', 'dibayar')
        ->when($outlet_id, function ($query, $outlet_id) {
            return $query->where('outlet_id', $outlet_id);
        })
        ->whereMonth('tgl', date('m'))
        ->select(
            DB::raw('DATE(tgl_bayar) AS tanggal'),
            DB::raw('sum(total_bayar) as jumlah')
        )
        ->groupBy('tanggal')
        ->get();

        $label = [];
        $jumlah = [];

        foreach ($charts as $chart) {
            $label[] = $chart->tanggal;
            $jumlah[] = $chart->jumlah;
        }

        $data = [
            'user' => $user,
            'transaksi' => $transaksi,
            'member' => $member,
            'label' => $label,
            'jumlah' => $jumlah,
            'outlet' => $outlet
        ];

        return view('welcome', $data);
    }
}

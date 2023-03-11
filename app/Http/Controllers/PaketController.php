<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Outlet;
use App\Models\LogActivity;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $pakets = Paket::join('outlets', 'outlets.id', 'pakets.outlet_id')
        ->when($search, function ($query, $search) {
            return $query->where('nama_paket', 'like', "%{$search}%");
        })
        ->select(
            'pakets.id as id',
            'nama_paket',
            'harga',
            'jenis',
            'diskon',
            'harga_akhir',
            'outlets.nama as outlet'
        )
        ->paginate();

        if($search){
            $pakets->appends(['search' => $search]);
        }

        $jenis = [
            'kiloan' => 'Kiloan',
            'kaos' => 'T-Shirt/Kaos',
            'bed_cover' => 'Bed Cover',
            'selimut' => 'Selimut',
            'lain' => 'Lainnya',
        ];

        $pakets->map(function ($row) use ($jenis) {
            $row->jenis = $jenis[$row->jenis];
            $row->harga = number_format($row->harga, 0, ',', '.');
            $row->diskon = number_format($row->diskon, 0, ',', '.');
            $row->harga_akhir = number_format($row->harga_akhir, 0, ',', '.');
            return $row;
        });

        return view('paket.index', [
            'pakets' => $pakets
        ]);
    }
    public function create()
    {
        LogActivity::Add('berhasil membuat paket');
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('paket.create', [
            'outlets' => $outlets
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|max:100',
            'harga' => 'required|numeric',
            'jenis' => 'required|in:kiloan,bed_cover,kaos,selimut,lain',
            'diskon'=>'nullable|numeric|min:0|', 
            'harga_akhir'=>'required|numeric|min:0|',
            'outlet_id' => 'required|exists:outlets,id'
        ], [], [
            'outlet_id' => 'Outlet'
        ]);

        Paket::create($request->all());
        LogActivity::Add('berhasil menambah paket');

        return redirect()->route('paket.index')
            ->with('message', 'success store');
    }
    public function show(Paket $paket)
    {
        return abort(404);
    }
    public function edit(Paket $paket)
    {
        LogActivity::Add('berhasil mengubah paket');
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('paket.edit', [
            'paket' => $paket,
            'outlets' => $outlets
        ]);
    }
    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required|max:100',
            'harga' => 'required|numeric',
            'jenis' => 'required|in:kiloan,bed_cover,kaos,selimut,lain',
            'diskon'=>'nullable|numeric|min:0|', //diskon
            'harga_akhir'=>'required|numeric|min:0|', //harga_diskon
            'outlet_id' => 'required|exists:outlets,id'
        ], [], [
            'outlet_id' => 'Outlet'
        ]);

        $paket->update($request->all());
        LogActivity::Add('berhasil mengubah paket');
        return redirect()->route('paket.index')
            ->with('message', 'success update');
    }
    public function destroy(Paket $paket)
    {
        LogActivity::Add('berhasil menghapus paket');
        $paket->delete();
        return back()->with('message', 'success delete');
    }
}

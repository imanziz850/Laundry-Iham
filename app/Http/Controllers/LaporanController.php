<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Transaksi;
use App\Models\LogActivity;
use DB;
class LaporanController extends Controller
{
    public function index()
    {
        $outlets = Outlet::select('id as value', 'nama as option')->get();

        $bulan = [
            ['option' => 'Januari', 'value' => '1'],
            ['option' => 'Februari', 'value' => '2'],
            ['option' => 'Maret', 'value' => '3'],
            ['option' => 'April', 'value' => '4'],
            ['option' => 'Mei', 'value' => '5'],
            ['option' => 'Juni', 'value' => '6'],
            ['option' => 'Juli', 'value' => '7'],
            ['option' => 'Agustus', 'value' => '8'],
            ['option' => 'September', 'value' => '9'],
            ['option' => 'Oktober', 'value' => '10'],
            ['option' => 'November', 'value' => '11'],
            ['option' => 'Desember', 'value' => '12'],
        ];

        $tahun = Transaksi::select(DB::raw('YEAR(tgl) tahun'))
            ->groupBy('tahun')
            ->get();
        $tahun->map(function ($row) {
            $row->option = $row->tahun;
            $row->value = $row->tahun;
        });

        return view('laporan.index', [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'outlets' => $outlets
        ]);
    }

    public function harian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'outlet_id' => 'required'
        ]);

        $outlet = Outlet::find($request->outlet_id);

        $data = Transaksi::join('users', 'users.id', 'transaksis.user_id')
        ->join('members', 'members.id', 'transaksis.member_id')
        ->where('dibayar', 'dibayar')
        ->where('transaksis.outlet_id', $request->outlet_id)
        ->whereDate('tgl', $request->tanggal)
        ->select(
            'members.nama as nama',
            'users.nama as kasir',
            'total_bayar',
            'tgl'
        )
        ->get();
        LogActivity::Add('berhasil membuat laporan harian');

        return view('laporan.harian', [
            'data' => $data,
            'outlet' => $outlet
        ]);
    }

    public function perbulan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|between:1,12',
            'tahun' => 'required|numeric|digits:4',
            'outlet_id' => 'required'
        ]);

        $outlet = Outlet::find($request->outlet_id);

        $data = Transaksi::where('dibayar', 'dibayar')
        ->whereMonth('tgl', $request->bulan)
        ->whereYear('tgl', $request->tahun)
        ->where('outlet_id', $request->outlet_id)
        ->select(
            DB::raw('DATE(tgl) AS tanggal'),
            DB::raw('sum(total_bayar) as jumlah')
        )
        ->groupBy('tanggal')
        ->get();
        LogActivity::Add('berhasil membuat laporan bulanan');


        return view('laporan.perbulan', [
            'data' => $data,
            'outlet' => $outlet
        ]);
    }
}

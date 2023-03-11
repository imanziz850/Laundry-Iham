@extends('layouts.report',['title'=>'Laporan Harian'])
@section('content')
<div class="container-fluid">
    <h3 class="text-center mt-2">
        {{ $outlet->nama }}
    </h3>
    <p class="text-center">
        <small>
            {{ $outlet->alamat }} <br>
            {{ $outlet->tlp }}
        </small>
    </p>
    <p>
        Judul : Laporan Harian<br>
        Tanggal : {{ date('1, d F Y', strtotime(request()->tanggal)) }}<br>
    </p>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Waktu</th>
                <th>Nama Kasir</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ( $data as $row )
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ date('d/m/Y H:i:s', strtotime($row->tgl)) }}</td>
                <td>{{ $row->kasir }}</td>
                <td>{{ number_format($row->total_bayar,0,',','.') }}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr class="border-bottom">
                    <th colspan="4" class="text-center">Total</th>
                    <th>{{ number_format($data->sum('total_bayar'),0,',','.') }}</th>
                </tr>
            </tfoot>
        </tbody>
    </table>
</div>
@endsection
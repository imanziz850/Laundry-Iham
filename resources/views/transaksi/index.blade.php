@extends('layouts.main',['title'=>'Transaksi'])
@section('content')
<x-content :title="['name'=>'Transaksi','icon'=>'fas fa-cash-register']">
<div class="card card-primary card-outline">
    <div class="card-header form-inline">
        @include('transaksi.add',['members'=>$members])
        <x-search/>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <th>No</th>
                <th>Invoice</th>
                <th>Nama</th>
                <th>Outlet</th>
                <th>Qty</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Batas Waktu</th>
                <th></th>
            </thead>
            <tbody>
                <?php $no = $transaksis->firstItem(); ?>
                @foreach ( $transaksis as $transaksi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <a href="{{ route('transaksi.detail',['transaksi'=>$transaksi->id]) }}">
                            {{ $transaksi->kode_invoice }}
                        </a>
                    </td>
                    <td>{{ $transaksi->nama }}</td>
                    <td>{{ $transaksi->outlet }}</td>
                    <td>{{ $transaksi->qty_total }}</td>
                    <td>{{ $transaksi->total_bayar }}</td>
                    <td>{{ $transaksi->status}}
                        @if ($transaksi->status == 'batal')
                            ('batal')
                        @else
                        ({{ $transaksi->dibayar }})
                        @endif
                    </td>
                    <td>{{ $transaksi->tgl }}</td>
                    <td>{{ $transaksi->batas_waktu }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-content>
@endsection
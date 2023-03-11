
@extends('layouts.main', ['title' => 'Transaksi'])
@section('content')
    <x-content :title="['name' => 'Transaksi','icon' => 'fas fa-cash-register']">

        @if (session('message') == 'success update')
            <x-alert-success type="update" />
        @endif
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <div class="form-group">Nama Member : {{ $member->nama }}</div>
                        <div class="form-group">No. Telepon : {{ $member->tlp }}</div>
                        <div class="form-group">Alamat : {{ $member->alamat }}</div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col">
                        <div class="form-group">Outlet : {{ $outlet->nama }}</div>
                        <div class="form-group">Kasir : {{ $user->nama }}</div>
                        <div class="form-group">
                            <a href="{{ route('transaksi.invoice', ['transaksi' => $transaksi->id]) }}" target="_blank" class="btn btn-primary">
                                Cetak Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Paket</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Sub Total</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $no = 1 ;
                        ?>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nama_paket }}</td>
                                <td>{{ $item->qty }} x {{ number_format($item->harga,0,',','.') }}</td>
                                <td>{{ number_format($item->qty * $item->harga,0,',','.') }}</td>
                                <td>{{ number_format($item->qty * $item->diskon_paket,0,',','.') }}</td>
                                <td>{{ number_format($item->sub_total,0,',','.') }}</td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Tidak ada paket dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transaksi->dibayar == 'belum_bayar')
                @include('transaksi.detail-form', ['transaksi' => $transaksi])
                @else
                @include('transaksi.detail-cash', ['transaksi' => $transaksi])
            @endif
        </div>
    </x-content>
@endsection
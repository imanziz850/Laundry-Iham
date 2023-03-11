@extends('layouts.main', ['title' => 'Transaksi'])
@section('content')
    <x-content :title="['name' => 'Transaksi','icon' => 'fas fa-cash-register']">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <div class="form-group">Nama Member : {{ $member->nama }}</div>
                        <div class="form-group">No. Telepon : {{ $member->tlp}}</div>
                        <div class="form-group">Alamat : {{ $member->alamat }}</div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col">
                        <form action="{{ route('transaksi.add', ['member' => $member->id]) }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col">Outlet</label>
                                <div class="col">
                                    <input type="text" value="{{ $outlet->nama }}" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col">Pilih Paket </label>
                                <div class="col">
                                    <x-select-transaksi
                                        name="paket"
                                        :data-option="$pakets" required
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col">Quantity </label>
                                <div class="col">
                                    <x-input-transaksi
                                        name="quantity" type="number" required
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col">Keterangan </label>
                                <div class="col">
                                    <x-textarea-transaksi
                                        name="keterangan" required
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-6 col">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Paket</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Sub Total</th>
                            <th>Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    {{ $item->quantity }} x {{ number_format($item->attributes->harga_awal,0,',','.') }}
                                </td>
                                <td>
                                    {{ number_format($item->quantity * $item->attributes->harga_awal,0,',','.') }}
                                </td>
                                <td>
                                    {{ number_format($item->quantity * $item->attributes->diskon,0,',','.') }}
                                </td>
                                <td>
                                    {{ number_format($item->quantity * $item->price,0,',','.') }}
                                </td>
                                <td>{{ $item->attributes->keterangan }}</td>
                                <td>
                                    <a href="{{ route('transaksi.updatecart', ['member' => $member->id ,'paket' => $item->id, 'type' => 'plus']) }}"
                                        class="btn p-0 text-primary">
                                        <i class="fas fa-plus-square"></i>
                                    </a>
                                    <a href="{{ route('transaksi.updatecart', ['member' => $member->id ,'paket' => $item->id, 'type' => 'min']) }}"
                                        class="btn p-0 text-warning">
                                        <i class="fas fa-minus-square"></i>
                                    </a> 
                                    <a href="{{ route('transaksi.delete', ['member' => $member->id, 'paket' => $item->id]) }}"
                                        class="btn p-0 text-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    Tidak ada paket dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <form action="{{ route('transaksi.store', ['member' => $member->id]) }}" method="post" class="card-body border-top">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col">Tanggal</label>
                            <div class="col">
                                <x-input-transaksi name="tanggal" :value="date('d/m/Y H:i:s')" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Batas Waktu</label>
                            <div class="col">
                                <x-input-transaksi name="batas_waktu" type="datetime-local" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col">
                        <div class="form-group row">
                            <label class="col">Total</label>
                            <div class="col">
                                <x-input-transaksi name="total" id="total" :value="$total" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Diskon Tambahan (Optional)</label>
                            <div class="col">
                                <x-input-transaksi name="diskon" id="diskon"  />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Biaya Tambahan (Optional)</label>
                            <div class="col">
                                <x-input-transaksi name="biaya_tambahan" id="biaya_tambahan"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Pajak (10%)</label>
                            <div class="col">
                                <x-input-transaksi name="pajak" id="pajak" :value="$total * 10 / 100" disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Total Bayar</label>
                            <div class="col">
                                <x-input-transaksi name="total_bayar" id="total_bayar" :value="$total + ($total * 10 / 100)" disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col">Uang Tunai / Cash (Optional)</label>
                            <div class="col">
                                <x-input-transaksi name="uang_tunai"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <a href="{{ route('transaksi.index') }}" class="btn btn-default">Kembali</a>
                                <a href="{{ route('transaksi.clear', ['member' => $member->id]) }}" class="btn btn-danger">Clear</a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-database mr-2"></i> Simpan / Proses
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-content>
@endsection

@push('js')
    <script>
        $('#diskon, #biaya_tambahan').keyup(function (e) {
            let t = parseInt($('#total').val());
            let d = parseInt($('#diskon').val());
            let bt = parseInt($('#biaya_tambahan').val());
            d = isNaN(d) ? 0 : d;
            bt = isNaN(bt) ? 0 : bt;
            let total = t - d + bt ;
            let pajak = Math.round( total * 10 / 100 );
            $("#pajak").val(pajak);
            $("#total_bayar").val(total + pajak);
        });
    </script>
@endpush
@extends('layouts.main', ['title' => 'Paket'])
@section('content')
    <x-content :title="[
        'name' => 'Paket',
        'icon' => 'fas fa-store-alt'
    ]">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <form action="{{ route('paket.update', ['paket' => $paket->id]) }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Edit Paket
                    </div>
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <x-input label="Nama Paket" name="nama_paket" :value="$paket->nama_paket"/>
                        <x-input label="Harga" name="harga" id="harga" :value="$paket->harga"/>
                        <x-input label="Diskon" name="diskon" :value="$paket->diskon" type="number"/>
                        <x-select label="Jenis" name="jenis" :data-option="[
                            ['option' => 'Kiloan', 'value' => 'kiloan'],
                            ['option' => 'T-Shirt/Kaos', 'value' => 'kaos'],
                            ['option' => 'Bed Cover', 'value' => 'bed_cover'],
                            ['option' => 'Selimut', 'value' => 'selimut'],
                            ['option' => 'Lainnya', 'value' => 'lain'],
                        ]" :value="$paket->jenis"/>
                        <x-select label="Outlet" name="outlet_id" :data-option="$outlets" :value="$paket->outlet_id" />
                        <x-input label="Harga Akhir" name="harga_akhir" id="harga_akhir" :value="$paket->harga_akhir" readonly />
                    </div>
                    <div class="card-footer">
                        <x-btn-update />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection


@push('js')
    <script>
        $(document).ready(function() {
            // Function to calculate final price
            function calculateFinalPrice() {
                let harga = parseInt($('#harga').val());
                let diskon = parseInt($('input[name="diskon"]').val());
                if (isNaN(diskon)) {
                    diskon = 0;
                }
                let harga_akhir = harga - diskon;
                if (harga_akhir < 0) {
                    $('#harga_akhir').val('');
                    alert('Diskon tidak boleh melebihi harga.');
                    $('button').attr('disabled', true);
                    return;
                }
                $('#harga_akhir').val(harga_akhir);
                $('button').attr('disabled', false);
            }

            // Calculate final price on input change
            $('#harga').on('input', function() {
                calculateFinalPrice();
            });

            $('input[name="diskon"]').on('input', function() {
                calculateFinalPrice();
            });
        });
    </script>
@endpush
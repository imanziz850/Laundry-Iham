@extends('layouts.main',['title'=>'Dashboard'])
@section('content')
<x-content :title="[
    'name'=>'Dashboard',
    'icon'=>'fas fa-home',
]">
    <div class="row">
        @can('admin-kasir')
        <x-box :data-box="[
            'label'=>'Transaksi',
            'icon'=>'fas fa-cash-register',
            'background'=>'bg-success',
            'href'=>route('transaksi.index'),
            'value'=>$transaksi->jumlah
        ]"/>
        <x-box :data-box="[
            'label'=>'Member',
            'icon'=>'fas fa-users',
            'background'=>'bg-primary',
            'href'=>route('member.index'),
            'value'=>$member->jumlah
        ]"/>
        @endcan
        @can('admin')
        <x-box :data-box="[
            'label'=>'Outlet',
            'icon'=>'fas fa-store-alt',
            'background'=>'bg-olive',
            'href'=>route('user.index'),
            'value'=>$outlet->jumlah
        ]"/>
        <x-box :data-box="[
            'label'=>'User',
            'icon'=>'fas fa-users',
            'background'=>'bg-danger',
            'href'=>route('user.index'),
            'value'=>$user->jumlah
        ]"/>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <div class="chart">
                <canvas id="chartTransaksi"></canvas>
            </div>
        </div>
    </div>
</x-content>
@endsection

@push('js')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js')}}"></script>
<script>
    var ctx = document.getElementById('chartTransaksi').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($label) ?>,
            datasets: [{
                label: "Pendapatan",
                data: <?= json_encode($jumlah) ?>,
            }]
        },
    });
</script>
@endpush
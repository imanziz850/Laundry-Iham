@extends('layouts.main',['title'=>'Member'])
@section('content')
<x-content :title="['name'=>'Member','icon'=>'fas fa-users']">
<div class="row">
    <div class="col-lg-4 col-md-6">
        <form
        class="card card-primary"
        method="POST"
        action="{{ route('member.store') }}">
            <div class="card-header">
                Buat Member
            </div>
            <div class="card-body">
                @csrf
                <x-input
                label="Nama"
                name="nama"/>

                <x-select
                label="Jenis Kelamin"
                name="jenis_kelamin"
                :data-option="[
                    ['option'=>'Laki-Laki','value'=>'L'],
                    ['option'=>'Perempuan','value'=>'P'],
                ]" />
                
                <x-input
                label="Telepon"
                name="tlp"/>

                <x-textarea
                label="Alamat"
                name="alamat" />

            </div>
            <div class="card-footer">
                <x-btn-submit/>
            </div>
        </form>
    </div>
</div>
</x-content>
@endsection
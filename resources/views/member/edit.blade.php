@extends('layouts.main',['title'=>'Member'])
@section('content')
<x-content :title="['name'=>'Member','icon'=>'fas fa-users']">
<div class="row">
    <div class="col-lg-4 col-md-6">
        <form
        class="card card-primary"
        method="POST"
        action="{{ route('member.update',['member'=>$member->id]) }}">
            <div class="card-header">
                Edit member
            </div>
            <div class="card-body">
                @csrf
                @method('PUT')
                <x-input
                label="Nama"
                name="nama"
                :value="$member->nama" />

                <x-select
                label="Jenis Kelamin"
                name="jenis_kelamin"
                :value="$member->jenis_kelamin"
                :data-option="[
                    ['option'=>'Laki-Laki','value'=>'L'],
                    ['option'=>'Perempuan','value'=>'P'],
                ]" />

                <x-input
                label="Telepone"
                name="tlp"
                :value="$member->tlp" />

                <x-textarea
                label="Alamat"
                name="alamat"
                :value="$member->alamat"/>
            </div>
            <div class="card-footer">
                <x-btn-update/>
            </div>
        </form>
    </div>
</div>
</x-content>
@endsection
@extends('layouts.main',['title'=>'User'])
@section('content')
<x-content :title="['name'=>'User','icon'=>'fas fa-user']">
<div class="row">
    <div class="col-lg-4 col-md-6">
        <form
        class="card card-primary"
        method="POST"
        action="{{ route('user.store') }}">
            <div class="card-header">
                Buat User
            </div>
            <div class="card-body">
                @csrf
                <x-input
                label="Nama"
                name="nama"/>

                <x-input
                label="Username"
                name="username"/>

                <x-select
                label="Role"
                name="role"
                :data-option="[
                    ['option'=>'Kasir','value'=>'kasir'],
                    ['option'=>'Pemilik','value'=>'owner'],
                    ['option'=>'Administrator','value'=>'admin']
                ]" />

                <x-select
                label="Outlet"
                name="outlet_id"
                :data-option="$outlets"/>

                <x-input
                label="Password"
                name="password"
                type="password" />

                <x-input
                label="Password Konfirmasi"
                name="password_confirmation"
                type="password" />

            </div>
            <div class="card-footer">
                <x-btn-submit/>
            </div>
        </form>
    </div>
</div>
</x-content>
@endsection
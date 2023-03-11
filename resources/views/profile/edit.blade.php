@extends('layouts.main',['title'=>'My Profile'])
@section('content')
<x-content :title="['name'=>'My Profile','icon'=>'fas fa-user']">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            @if (session('message') == 'success update')
            <x-alert-success type="update" />
            @endif

            <form 
            class="card card-primary"
            method="POST"
            action="{{ route('profile') }}">
                <div class="card-header"></div>
                <div class="card-body">
                @csrf
                <x-input
                label="Nama"
                name="nama"
                :value="$user->nama"/>

                <x-input
                label="Username"
                name="username"
                :value="$user->username"
                disabled/>

                <p class="text-muted">
                    Kosongkan password jika tidak mengganti password.
                </p>

                <x-input
                label="Password"
                name="password"
                type="password"/>

                <x-input
                label="Password Konfirmasi"
                name="password_confirmation"
                type="password"/>
             </div>
             <div class="card-footer">
                <x-btn-update/>
             </div>
         </form>
     </div>
 </div>
</x-content>
@endsection
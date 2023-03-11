@extends('layouts.main',['title'=>'Member'])
@section('content')
<x-content :title="['name'=>'Member','icon'=>'fas fa-users']">

@if (session('message') == 'success store')
<x-alert-success/>
@endif

@if (session('message') == 'success update')
<x-alert-success type="update"/>
@endif

@if (session('message') == 'success delete')
<x-alert-success type="delete"/>
@endif

<div class="card card-primary card-outline">
    <div class="card-header form-inline">
        <x-btn-add :href="route('member.create')"/>
        <x-search/>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>P/L</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $members->firstItem() ?>
                @foreach ( $members as $member)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $member->nama }}</td>
                    <td>{{ $member->jenis_kelamin }}</td>
                    <td>{{ $member->tlp }}</td>
                    <td>{{ $member->alamat }}</td>
                    <td>
                        <x-edit :href="route('member.edit',[
                            'member'=>$member->id
                        ])" />
                        <x-delete :data-url="route('member.destroy',[
                            'member'=>$member->id
                        ])" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $members->links('page') }}
    </div>
</div>
</x-content>
@endsection
@push('modal')
<x-modal-delete/>
@endpush
@extends('layouts.main',['title'=>'Outlet'])
@section('content')
<x-content :title="['name'=>'Outlet','icon'=>'fas fa-store-alt']">

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
        <x-btn-add :href="route('outlet.create')"/>
        <x-search/>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Outlet</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $outlets->firstItem() ?>
                @foreach ( $outlets as $outlet)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $outlet->nama }}</td>
                    <td>{{ $outlet->tlp }}</td>
                    <td>{{ $outlet->alamat }}</td>
                    <td>
                        <x-edit :href="route('outlet.edit',[
                            'outlet'=>$outlet->id
                        ])" />
                        <x-delete :data-url="route('outlet.destroy',[
                            'outlet'=>$outlet->id
                        ])" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $outlets->links('page') }}
    </div>
</div>
</x-content>
@endsection
@push('modal')
<x-modal-delete/>
@endpush
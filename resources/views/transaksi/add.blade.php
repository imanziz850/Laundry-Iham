<div class="input-group">
    <select id="member" style="width: 200px">
        <option value="">Pilih :</option>
        @foreach ($members as $member)
        <option value="{{ $member->id }}">{{ $member->nama }}</option>
        @endforeach
    </select>
    <div class="input-group-append">
        <button class="btn btn-success" id="add">
            Buat Transaksi
        </button>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('#member').select2();
    $('#add').click(function() {
        let id = $('#member').val();
        let url = "{{ route('transaksi.create',['member'=>'member_id']) }}";
        if (id) {
            window.location.href = url.replace('member_id', id);
        }
    })
</script>
@endpush
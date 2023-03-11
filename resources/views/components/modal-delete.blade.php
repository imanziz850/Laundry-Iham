<div
 class="modal fade"
 id="modalDelete"
 data-backdrop="static"
 data-keyboard="false"
 tabindex="-1">
    <form class="modal-dialog modal-sm" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah yakin akan dihapus ?
                @csrf
                @method('delete')
            </div>
            <div class="modal-footer">
                <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal">
                    Batal
                </button>
                <x-btn-delete />
            </div>
        </div>
    </form>
</div>

@push('js')
<script>
    $(function(){
    $(document).on('click','.btn-delete', function(){
        let url = $(this).attr('data-url');
        $('#modalDelete form').attr('action',url);
        $('#modalDelete').modal('show');
    });
});
</script>
@endpush
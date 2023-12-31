<div class="nk-upload-form">
    <h5 class="title mb-3">Upload</h5>
</div>
<div class="nk-upload-list">

    <form action="{{ route('upload-update') }}" method="POST" id="form-action" enctype="multipart/form-data">
        @csrf
        <div class="row g">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" name="id_ubah" value="{{ Crypt::encrypt($data_upload->id) }}">
                    <input type="hidden" name="tipe" value="{{ $data_upload->type }}">
                    <label class="form-label" for="name">Nama</label>
                    <div class="form-control-wrap">
                        <input name="name" type="text" readonly class="form-control form-control-sm" id="name"
                            value="{{ $data_upload->name }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="default-06">Pilih File</label>
                    <div class="form-control-wrap">
                        <div class="custom-file">
                            <input type="file" multiple class="custom-file-input" name="file_upload" id="file_upload">
                            <label class="custom-file-label" for="file_upload">Pilih</label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Upload</button>
                </div>
            </div>
        </div>
    </form>
</div>
{{-- <div class="nk-modal-action justify-end">
    <ul class="btn-toolbar g-4 align-center">
        <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
        <li><button class="btn btn-primary">Add Files</button></li>
    </ul>
</div> --}}

<script>
    $(document).ready(function() {

        $('.btn-action').on("click", function(e) {

            e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda akan mengUpload Data?",
                    showCancelButton: true,
                    confirmButtonColor: "#2c3782",
                    confirmButtonText: 'Lanjut',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $('form#form-action').submit();
                    }
                });

        });
    });
</script>
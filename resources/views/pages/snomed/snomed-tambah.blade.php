<div class="nk-upload-form">
    <h5 class="title mb-3">Tambah Snomed</h5>
</div>
<div class="nk-upload-list">
    <form action="{{ route('snomed-simpan') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="snomed_code">Kode</label>
                    <div class="form-control-wrap">
                        <input name="snomed_code" type="text" class="form-control form-control-sm" id="snomed_code"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    <label class="form-label" for="snomed_display">Keterangan</label>
                    <div class="form-control-wrap">
                        <input name="snomed_display" type="text" class="form-control form-control-sm"
                            id="snomed_display" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Simpan</button>
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
                    text: "Anda akan Menambahkan Data?",
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
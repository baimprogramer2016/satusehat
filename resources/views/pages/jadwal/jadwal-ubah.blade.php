<div class="nk-upload-form">
    <h5 class="title mb-3">Atur Jadwal</h5>
</div>
<div class="nk-upload-list">

    <form action="{{ route('jadwal-update') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="id_ubah" value="{{ Crypt::encrypt($data_jadwal->id) }}">
                    <label class="form-label" for="name">Nama</label>
                    <div class="form-control-wrap">
                        <input name="name" type="text" readonly class="form-control form-control-sm" id="name"
                            value="{{ $data_jadwal->name }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="cron">Cron</label>
                    <div class="form-control-wrap">
                        <input name="cron" value="{{ $data_jadwal->cron }}" type="text"
                            class="form-control form-control-sm" id="cron" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">

                    <div class="custom-control custom-control-sm custom-checkbox mt-3">
                        <input name="status" type="checkbox" {{ ($data_jadwal->status==1) ? 'checked':''}}
                        class="custom-control-input"
                        id="status">
                        <label class="custom-control-label" for="status">Aktif / Tidak Aktif</label>
                    </div>
                </div>
            </div>



            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Ubah</button>
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
                    text: "Anda akan Mengubah Data?",
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
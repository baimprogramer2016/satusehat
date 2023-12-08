<div class="nk-upload-form">
    <h5 class="title mb-3">Ubah IHS</h5>
</div>
<div class="nk-upload-list">

    <form action="{{ route('pasien-update-ihs') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" name="id_ubah" value="{{ Crypt::encrypt($data_patient->id) }}">
                    <label class="form-label" for="original_code">Kode</label>
                    <div class="form-control-wrap">
                        <input name="original_code" type="text" readonly class="form-control form-control-sm"
                            id="original_code" value="{{ $data_patient->original_code }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="name">Nama</label>
                    <div class="form-control-wrap">
                        <input name="name" type="text" readonly class="form-control form-control-sm" id="name"
                            value="{{ $data_patient->name }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="nik">NIK</label>
                    <div class="form-control-wrap">
                        <input name="nik" readonly type="text" class="form-control form-control-sm" id="nik"
                            value="{{ $data_patient->nik }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Nama IHS</label>
                    <div class="form-control-wrap">
                        <input name="satusehat_id" readonly value="{{ $nama_ihs }}" type="text"
                            class="form-control bg-{{ $color_ihs }} text-white form-control-sm">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="satusehat_id">IHS</label>
                    <div class="form-control-wrap">
                        <input name="satusehat_id" readonly value="{{ $id_ihs }}" type="text"
                            class="form-control bg-{{ $color_ihs }} text-white form-control-sm" id="satusehat_id">
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    @if($id_ihs > 0)
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Update ID IHS</button>
                    @endif
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
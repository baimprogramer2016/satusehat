<div class="nk-upload-form">
    <h5 class="title mb-3">Struktur Organisasi</h5>
</div>
<div class="nk-upload-list">
    <div class="row justify-content-center">
        @if (file_exists(public_path('uploads/struktur_organization.pdf')))
        <iframe src="{{ asset('uploads/struktur_organization.pdf') }}" width="100%" height="450">
            This browser does not support PDFs. Please download the PDF to view it: <a
                href="{{ asset('uploads/struktur_organization.pdf') }}">Download PDF</a>
        </iframe>
        @endif
    </div>

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
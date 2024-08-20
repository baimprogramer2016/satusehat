<div class="nk-upload-form">
    <h5 class="title mb-3">Pilih Kode</h5>
    <p>Deskripsi : {{ Ucwords($data_rencana_tindak_lanjut_master['display']) }}</p>
    <input type="hidden" id="id_rencana_tindak_lanjut_kode" value="{{ $data_rencana_tindak_lanjut_master['id'] }}">
</div>
{{--
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"> --}}
<div class="nk-upload-list">

    <div class="card card-preview">

        <table class="table table-bordered data-table-kode mt-3">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Sistem</th>
                    <th>Pilih</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div><!-- .card-preview -->
</div>
{{-- <div class="nk-modal-action justify-end">
    <ul class="btn-toolbar g-4 align-center">
        <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
        <li><button class="btn btn-primary">Add Files</button></li>
    </ul>
</div> --}}
{{-- <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script> --}}
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


$(function () {

var id_rencana_tindak_lanjut_kode = document.getElementById('id_rencana_tindak_lanjut_kode').value;
var url   ='{{ route("rencana-tindak-lanjut-master-data",":id") }}';
url       = url.replace(':id',id_rencana_tindak_lanjut_kode);


var table = $('.data-table-kode').DataTable({
    processing: true,
    serverSide: true,
    language : {
        sLengthMenu: "Show _MENU_"
    },
    ajax: url,
    columns: [

    //   {data: 'id', name: 'id'},
        {data: 'code', name: 'code'},
        {data: 'display', name: 'display'},
        {data: 'code_system', name: 'code_system'},
        {data: 'pilih_kode', name: 'pilih_kode', orderable: false, searchable: false},
    ]
    });
});
</script>
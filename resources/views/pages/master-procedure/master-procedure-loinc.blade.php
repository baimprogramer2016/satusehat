<div class="nk-upload-form">
    <h5 class="title mb-3">Pilih Kode Loinc</h5>
    <p>Nama Obat : {{ $data_master_procedure['original_display'] }}</p>
    <input type="hidden" id="id_master_procedure" value="{{ $data_master_procedure['id'] }}">
</div>
{{--
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"> --}}
<div class="nk-upload-list">

    <div class="card card-preview">

        <table class="table table-bordered data-table-loinc mt-3">
            <thead>
                <tr>
                    <th>Kode Loinc</th>
                    <th>Keterangan</th>
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

var id_master_procedure = document.getElementById('id_master_procedure').value;
var url   ='{{ route("master-procedure-loinc-data",":id") }}';
url       = url.replace(':id',id_master_procedure);

var table = $('.data-table-loinc').DataTable({
    processing: true,
    serverSide: true,
    language : {
        sLengthMenu: "Show _MENU_"
    },
    ajax: url,
    columns: [

    //   {data: 'id', name: 'id'},
        {data: 'loinc_code', name: 'loinc_code'},
        {data: 'loinc_display', name: 'loinc_display'},
        {data: 'pilih_loinc', name: 'pilih_loinc', orderable: false, searchable: false},
    ]
    });
});
</script>
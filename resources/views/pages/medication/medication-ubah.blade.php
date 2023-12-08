<div class="nk-upload-form">
    <h5 class="title mb-3">Pilih Kode KFA</h5>
    <p>Nama Obat : {{ $data_medication['display_name'] }}</p>
    <input type="hidden" id="id_medication" value="{{ $data_medication['id'] }}">
</div>
{{--
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"> --}}
<div class="nk-upload-list">

    <div class="card card-preview">

        <table class="table table-bordered data-table-kfa mt-3">
            <thead>
                <tr>
                    <th>Kode KFA</th>
                    <th>Nama KFA</th>
                    <th>Kode Zat Aktif</th>
                    <th>Zat Aktif</th>
                    <th>Kekuatan</th>
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

var id_medication = document.getElementById('id_medication').value;
var url   ='{{ route("medication-kfa-data",":id") }}';
url       = url.replace(':id',id_medication);

var table = $('.data-table-kfa').DataTable({
    processing: true,
    serverSide: true,
    language : {
        sLengthMenu: "Show _MENU_"
    },
    ajax: url,
    columns: [

    //   {data: 'id', name: 'id'},
        {data: 'kode_kfa', name: 'kode_kfa'},
        {data: 'nama_kfa', name: 'nama_kfa'},
        {data: 'kode_pa', name: 'kode_pa'},
        {data: 'nama_pa', name: 'nama_pa'},
        {data: 'kolom_numerator', name: 'kolom_numerator'},
        {data: 'pilih_kfa', name: 'pilih_kfa', orderable: false, searchable: false},
    ]
    });
});
</script>
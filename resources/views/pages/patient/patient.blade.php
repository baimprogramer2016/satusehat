@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Pasien</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan atau Master Data</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="{{ route('pasien-tambah') }}"
                                class="btn btn-white btn-dim btn-outline-primary mr-2"><em
                                    class="icon ni ni-plus"></em><span>Tambah </span></a>
                            <button class="btn btn-white btn-dim btn-outline-primary btn-run-job"
                                data-toggle="modal"><em class="icon ni ni-play"></em><span>Update ID
                                    IHS<br>Max {{ env('MAX_RECORD') }} Record</span></button>
                        </li>
                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-12">

            <div class="card card-bordered card-full">
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table mt-3">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nik</th>
                                    <th>Nama</th>
                                    <th>ID</th>
                                    <th>Pesan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md" id="content-modal">
                {{-- Content Here --}}
                <div class='d-flex justify-content-center' id='loading-process'>
                    <div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div>
                </div>
                {{-- End Content --}}
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
@endsection
@push('script')

<script type="text/javascript">
    $(function () {

      var table = $('.data-table').DataTable({
          stateSave: true,
          processing: true,
          serverSide: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: "{{ route('pasien') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'original_code', name: 'original_code'},
              {data: 'nik', name: 'nik'},
              {data: 'name', name: 'name'},
              {data: 'satusehat_id', name: 'satusehat_id'},
              {data: 'satusehat_message', name: 'satusehat_message'},
              {data: 'status_update', name: 'status_update'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });

    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("pasien-response-ss", ":id") }}';
        url         = url.replace(':id',id);
        $.ajax({
            type:"GET",
            url:url,
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    function modalUbah(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("pasien-ubah", ":id") }}';
        url         = url.replace(':id',id);

        $.ajax({
            type:"GET",
            url:url,
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    function modalUpdateIhs(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("pasien-ubah-ihs", ":id") }}';
        url         = url.replace(':id',id);

        $.ajax({
            type:"GET",
            url:url,
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    $(document).ready(function() {

        $('.btn-run-job').on("click", function(e) {

            e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Job Update ID IHS Pasien Akan dijalankan Maksimal {{ env('MAX_RECORD') }} Record",
                    showCancelButton: true,
                    confirmButtonColor: "#2c3782",
                    confirmButtonText: 'Jalankan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        var url     = '{{ route("pasien-run-job") }}';
                        $.ajax({
                            type:"POST",
                            url:url,
                            data: {
                                _token : "{{csrf_token()}}"
                            },
                            success: function(response)
                            {
                                // console.log(JSON.stringify(response));
                                toastMessage(response,"success");
                            }
                        })
                    }
                });

        });
    });


    function toastMessage(message,color){
        (function (NioApp, $) {
            'use strict'; // Uses
                toastr.clear();
                NioApp.Toast('<p>'+message+'</p>',color);
        })(NioApp, jQuery);
    }

</script>

@endpush
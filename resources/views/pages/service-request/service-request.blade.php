@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Service Request</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Kunjungan Pasien</p>
            </div>
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
                                    <th>Kode Service</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Nama Pasien</th>
                                    <th>Nama Dokter</th>
                                    <th>Kategori</th>
                                    <th>Catatan</th>
                                    <th>Tanggal</th>
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
    <div class="modal-dialog modal-xl  modal-dialog-top" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md" id="content-modal">
                {{-- Content Here --}}
                <div class='d-flex justify-content-center ' id='loading-process'>
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
          processing: true,
          serverSide: true,
          stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: "{{ route('service-request') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'encounter_original_code', name: 'encounter_original_code'},
              {data: 'identifier', name: 'identifier', orderable: false, searchable: true},
              {data: 'satusehat_id', name: 'satusehat_id'},
              {data: 'subject_display', name: 'subject_display'},
              {data: 'participant_individual_display', name: 'participant_individual_display'},
              {data: 'category_display', name: 'category_display'},
              {data: 'reason_text', name: 'reason_text'},
              {data: 'authored_on', name: 'authored_on'},
              {data: 'status', name: 'status', orderable: false, searchable: false},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

    });



    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("service-request-response-ss", ":id") }}';
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

    function modalKirimSS(id)
    {
        loadingProcess(); //dari custom.js

        var url     = '{{ route("service-request-modal-kirim-ss", ":id") }}';
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


    function kirimSatuSehat(id)
    {
        // loadingProcess(); //dari custom.js

        $(".btn-action").html('Proses Kirim....')
        $(".btn-action").prop("disabled", true);
        $(".result-message").html('...');
        var url     = '{{ route("service-request-kirim-ss", ":id") }}';
        url         = url.replace(':id',id);

        $.ajax({
            type:"POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",

            },
            url:url,
            success: function(response)
            {//resourceType = OperationOutcome

                result = JSON.parse(response);
                // console.log(result.resourceType)
                if(result.resourceType == 'OperationOutcome')
                {
                    $(".result-message").html("<i class='text-danger'>Gagal di kirim</i>");
                    $(".btn-action").hide();
                }else
                {
                    $(".result-message").html("<i class='text-success'>Berhasil di kirim</i>");
                    location.reload();
                    $(".btn-action").html('Selesai');
                }

                $("#response_ss").val(response);

            }
        })
    }

</script>

@endpush
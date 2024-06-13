@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Allergy</h3>
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
                <div class="row m-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label text-secondary" for="default-06">Status</label>
                            <div class="form-control-wrap">
                                <div class="form-control-select">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-report"></em>
                                    </div>
                                    <select class="form-control form-control-sm" id="status_kirim">
                                        <option value="success">
                                            Terkirim
                                        </option>
                                        <option value="failed">
                                            Gagal Terkirim
                                        </option>
                                        <option value="waiting">
                                            Menunggu
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Awal</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control form-control-sm date-picker"
                                    data-date-format="yyyy-mm-dd" id="tanggal_awal" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tanggal Akhir</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input type="text" class="form-control form-control-sm date-picker"
                                    data-date-format="yyyy-mm-dd" id="tanggal_akhir" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 align-content-end">
                        <button id="btn-filter" class="btn btn-sm btn-round btn-secondary text-center"><em
                                class="icon ni ni-search"></em><span></span> Search</button>
                    </div>
                </div>
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table mt-3">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Idendtifier</th>
                                    <th>Nama Pasien</th>
                                    <th>Catatan</th>
                                    <th>Satu Sehat ID</th>
                                    <th>Tanggal</th>
                                    <th>Response</th>
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
    function load_datatable(p_status_kirim = 'success', p_tanggal_awal = null, p_tanggal_akhir=null){

    //mendapatkan status param URL sebagai filter saat kirim data
    const getParams = new URLSearchParams(window.location.search);

    const par_status_kirim = getParams.get('status_kirim');
    p_status_kirim = par_status_kirim;
    document.getElementById('status_kirim').value = par_status_kirim;

    const par_tanggal_awal = getParams.get('tanggal_awal');
    p_tanggal_awal = par_tanggal_awal;
    document.getElementById('tanggal_awal').value = par_tanggal_awal;

    const par_tanggal_akhir = getParams.get('tanggal_akhir');
    p_tanggal_akhir = par_tanggal_akhir;
    document.getElementById('tanggal_akhir').value = par_tanggal_akhir;


      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },

          ajax: {
            url : "{{ route('allergy') }}",
            data : {
                status_kirim:  p_status_kirim,
                tanggal_awal:  p_tanggal_awal,
                tanggal_akhir:  p_tanggal_akhir,
            }
          },
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'encounter_original_code', name: 'encounter_original_code'},
              {data: 'identifier_value', name: 'identifier_value'},
              {data: 'subject_reference_display', name: 'subject_reference_display'},
              {data: 'coding_display', name: 'coding_display'},
              {data: 'satusehat_id', name: 'satusehat_id'},
              {data: 'recorded_date', name: 'recorded_date'},
              {data: 'satusehat_statuscode', name: 'satusehat_statuscode'},
              {data: 'status', name: 'status', orderable: false, searchable: false},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    };


    load_datatable();

    $("#btn-filter").click(function(){

        const status_kirim = ($("#status_kirim").val() === '') ? '' : $("#status_kirim").val();
        const tanggal_awal = ($("#tanggal_awal").val() === '') ? '' : $("#tanggal_awal").val();
        const tanggal_akhir =($("#tanggal_akhir").val() === '') ? '' : $("#tanggal_akhir").val();

        //menangani filter
        const url = new URL(window.location);
        const params = new URLSearchParams(url.search);
        window.history.replaceState({}, '', `${url.pathname}?status_kirim=${status_kirim}&tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`);

        $('.data-table').DataTable().destroy();
        load_datatable(status_kirim, tanggal_awal, tanggal_akhir);
    })


    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("allergy-response-ss", ":id") }}';
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

        var url     = '{{ route("allergy-modal-kirim-ss", ":id") }}';
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
        var url     = '{{ route("allergy-kirim-ss", ":id") }}';
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
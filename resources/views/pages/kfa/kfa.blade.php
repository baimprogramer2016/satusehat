@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">KFA</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan atau Master Data</p>
            </div>
        </div><!-- .nk-block-head-content -->

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-8">

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
                                    <th>Nama</th>
                                    <th>Template</th>
                                    <th>Kekuatan</th>
                                    <th>Zat Aktif</th>
                                    <th>Dosis</th>
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
          processing: true,
          serverSide: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: "{{ route('kfa') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'kode_kfa', name: 'kode_kfa'},
              {data: 'nama_kfa', name: 'nama_kfa'},
              {data: 'pv', name: 'pv'},
              {data: 'pa', name: 'pa'},
              {data: 'kekuatan', name: 'kekuatan'},
              {data: 'nama_sediaan', name: 'nama_sediaan'},
          ]
      });

    });

    function modalResponseSS(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("praktisi-response-ss", ":id") }}';
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
        var url     = '{{ route("praktisi-ubah", ":id") }}';
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
        var url     = '{{ route("praktisi-ubah-ihs", ":id") }}';
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
</script>

@endpush
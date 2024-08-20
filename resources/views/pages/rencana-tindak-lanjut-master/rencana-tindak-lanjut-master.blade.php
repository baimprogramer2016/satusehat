@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Rencana Tindak Lanjut Master</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan atau Master Data</p>
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
                                    <th>Nama</th>
                                    <th>Pilih Kode</th>
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
    <div class="modal-dialog modal-xl modal-dialog-top" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md" id="content-modal">
                {{-- Content Here --}}

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
          ajax: "{{ route('rencana-tindak-lanjut-master') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'code', name: 'code'},
              {data: 'display', name: 'display'},
              {data: 'daftar_kode', name: 'daftar_kode', orderable: false, searchable: false},
          ]
      });
    });


    function modalViewKode(event)
    {
        var id_input_element = event.target.name.replace('id_rencana_tindak_lanjut_master_', '');


        // console.log(display_name)
        loadingProcess(); //dari custom.js
        var url     ='{{ route("rencana-tindak-lanjut-master-kode",":id") }}';
        url         = url.replace(':id',id_input_element);

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

    // saat dipilih kfa nya , akan langsung update ke dalam table

    function updateKode(id_rencana_tindak_lanjut_master,kode_rencana_tindak_lanjut)
    {
        var inputElement = document.querySelector('input[name="id_rencana_tindak_lanjut_master_'+id_rencana_tindak_lanjut_master+'"]');


        var param = {
            id_rencana_tindak_lanjut_master : parseInt(id_rencana_tindak_lanjut_master),
            kode_rencana_tindak_lanjut : kode_rencana_tindak_lanjut,
            _token : "{{csrf_token()}}"
        }

        // console.log(param);

        $.ajax({
            type:"POST",
            url: "{{ route('rencana-tindak-lanjut-master-kode-update') }}",
            data: param,
            success: function(response)
            {
                // console.log(response.kfa_code+ '-'+ inputElement.value)
                if(response.kode_rencana_tindak_lanjut != inputElement.value)
                {
                    inputElement.value = kode_rencana_tindak_lanjut;
                    toastMessage("Kode Berhasil di Update","success");
                }
                else{
                    toastMessage("Tidak ada Perubahan","warning");
                }
            }
        })
    }


function toastMessage(message,color){
    (function (NioApp, $) {
        'use strict'; // Uses
            toastr.clear();
            NioApp.Toast('<p>'+message+'</p>',color);
    })(NioApp, jQuery);
}
</script>
@endpush
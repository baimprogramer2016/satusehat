@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Medication</h3>
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
                                    <th>Isi</th>
                                    <th>Unit</th>
                                    <th>Pack</th>
                                    <th>Dosis</th>
                                    <th>Pilih KFA</th>
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
          ajax: "{{ route('medication') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'original_code', name: 'original_code'},
              {data: 'display_name', name: 'display_name'},
              {data: 'value', name: 'value'},
              {data: 'unit', name: 'unit'},
              {data: 'packaging', name: 'packaging'},
              {data: 'dose', name: 'dose'},
              {data: 'daftar_kfa', name: 'daftar_kfa', orderable: false, searchable: false},
          ]
      });
    });


    function modalViewKfa(event)
    {
        var id_input_element = event.target.name.replace('id_medication_', '');


        // console.log(display_name)
        loadingProcess(); //dari custom.js
        var url     ='{{ route("medication-kfa",":id") }}';
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

    function updateKfa(id_medication,kode_kfa)
    {
        var inputElement = document.querySelector('input[name="id_medication_'+id_medication+'"]');


        var param = {
            id_medication : id_medication,
            kode_kfa : kode_kfa,
            _token : "{{csrf_token()}}"
        }

        $.ajax({
            type:"POST",
            url: "{{ route('medication-kfa-update') }}",
            data: param,
            success: function(response)
            {
                // console.log(response.kfa_code+ '-'+ inputElement.value)
                if(response.kfa_code != inputElement.value)
                {
                    inputElement.value = kode_kfa;
                    toastMessage("Kfa Berhasil di Update","success");
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

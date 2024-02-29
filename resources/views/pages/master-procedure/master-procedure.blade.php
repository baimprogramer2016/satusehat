@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Master Tindakan</h3>
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
                                    <th>Snomed</th>
                                    <th>Loinc</th>
                                    <th>Category</th>
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
          stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
          ajax: "{{ route('master-procedure') }}",
          columns: [

            //   {data: 'id', name: 'id'},
              {data: 'original_code', name: 'original_code'},
              {data: 'original_display', name: 'original_display'},
              {data: 'daftar_snomed', name: 'daftar_snomed', orderable: false, searchable: false},
              {data: 'daftar_loinc', name: 'daftar_loinc', orderable: false, searchable: false},
              {data: 'daftar_category', name: 'daftar_category', orderable: false, searchable: false},
          ]
      });
    });


    //snomed
    function modalViewSnomed(event)
    {
        var id_input_element = event.target.name.replace('id_master_procedure_', '');


        // console.log(display_name)
        loadingProcess(); //dari custom.js
        var url     ='{{ route("master-procedure-snomed",":id") }}';
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

    function updateSnomed(id_master_procedure,snomed_code)
    {
        var inputElement = document.querySelector('input[name="id_master_procedure_'+id_master_procedure+'"]');


        var param = {
            id_master_procedure : id_master_procedure,
            snomed_code : snomed_code,
            _token : "{{csrf_token()}}"
        }

        $.ajax({
            type:"POST",
            url: "{{ route('master-procedure-snomed-update') }}",
            data: param,
            success: function(response)
            {
                // console.log(response.kfa_code+ '-'+ inputElement.value)
                if(response.snomed_code != inputElement.value)
                {
                    inputElement.value = snomed_code;
                    toastMessage("Snomed Berhasil di Update","success");
                }
                else{
                    toastMessage("Tidak ada Perubahan","warning");
                }
            }
        })
    }

    //loinc
    function modalViewLoinc(event)
    {
        var id_input_element = event.target.name.replace('id_master_procedure_loinc_', '');


        // console.log(display_name)
        loadingProcess(); //dari custom.js
        var url     ='{{ route("master-procedure-loinc",":id") }}';
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

    function updateLoinc(id_master_procedure,loinc_code)
    {

        var inputElement = document.querySelector('input[name="id_master_procedure_loinc_'+id_master_procedure+'"]');


        var param = {
            id_master_procedure : id_master_procedure,
            loinc_code : loinc_code,
            _token : "{{csrf_token()}}"
        }
        console.log(param)
        $.ajax({
            type:"POST",
            url: "{{ route('master-procedure-loinc-update') }}",
            data: param,
            success: function(response)
            {
                // console.log(response.kfa_code+ '-'+ inputElement.value)
                if(response.loinc_code != inputElement.value)
                {
                    inputElement.value = loinc_code;
                    toastMessage("Loinc Berhasil di Update","success");
                }
                else{
                    toastMessage("Tidak ada Perubahan","warning");
                }
            }
        })
    }

    function modalViewCategory(event)
    {
        var id_input_element = event.target.name.replace('id_master_procedure_category_', '');


        // console.log(display_name)
        loadingProcess(); //dari custom.js
        var url     ='{{ route("master-procedure-category",":id") }}';
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


    function updateCategory(id_master_procedure,category_request)
    {

        var inputElement = document.querySelector('input[name="id_master_procedure_category_'+id_master_procedure+'"]');

        if(category_request !=''){
            category_request = category_request.replace('|',' ')
        }

        var param = {
            id_master_procedure : id_master_procedure,
            category_request : category_request,
            _token : "{{csrf_token()}}"
        }
        console.log(param)
        $.ajax({
            type:"POST",
            url: "{{ route('master-procedure-category-update') }}",
            data: param,
            success: function(response)
            {
                // console.log(response.kfa_code+ '-'+ inputElement.value)
                if(response.category_request != inputElement.value)
                {
                    inputElement.value = category_request;
                    toastMessage("Kategori Berhasil di Update","success");
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
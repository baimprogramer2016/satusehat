@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Sinkronisasi</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">

            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="#file-upload" onClick="modalTambah()"
                                class="btn btn-white btn-dim btn-outline-primary" data-toggle="modal"><em
                                    class="icon ni ni-plus"></em><span>Tambah</span></a>
                        </li>
                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
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
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <th>Part</th>
                                    <th>Page</th>
                                    <th>Sukses</th>
                                    <th>Jadwal</th>
                                    <th>Terakhir</th>
                                    <th>Status</th>
                                    <th>Start/Stop</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_sinkronisasi as $item_sinkronisasi)
                                <tr>
                                    <td>{{ $item_sinkronisasi->kode }}</td>
                                    <td>{{ $item_sinkronisasi->description }}</td>
                                    <td>{{ $item_sinkronisasi->record }}</td>
                                    <td>{{ $item_sinkronisasi->part }}</td>
                                    <td>{{ $item_sinkronisasi->page }}</td>
                                    <td>{{ $item_sinkronisasi->upload }}</td>
                                    <td>{{ $item_sinkronisasi->schedule }}</td>
                                    <td>{{ $item_sinkronisasi->last_process }}</td>
                                    <td>{{ $item_sinkronisasi->status }}</td>
                                    <td><button class="btn btn-success"><em class="icon ni ni-play"></em></button>
                                    </td>
                                    <td>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a onClick="return modalUbah('{{ Crypt::encrypt($item_sinkronisasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-edit"></em><span>Ubah</span></a>
                                                    </li>
                                                    <li><a onClick="return modalHapus('{{ Crypt::encrypt($item_sinkronisasi->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-trash"></em><span>Hapus</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

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
<script>
    $('.data-table').DataTable({
          language : {
                sLengthMenu: "Show _MENU_"
            },
        });

    function modalTambah()
    {
        loadingProcess(); //dari custom.js
            $.ajax({
            type:"GET",
            url:"{{ route('sinkronisasi-tambah') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }
    function modalHapus(id)
    {
        // loadingProcess(); //dari custom.js
        // var url     = '{{ route("organisasi-hapus", ":id") }}';
        // url         = url.replace(':id',id);
        // $.ajax({
        //     type:"GET",
        //     url:url,
        //     success: function(response)
        //     {
        //         $("#content-modal").html("");
        //         $("#content-modal").html(response);
        //     }
        // })
    }
    function modalUbah(id)
    {
        // loadingProcess(); //dari custom.js
        // var url     = '{{ route("organisasi-ubah", ":id") }}';
        // url         = url.replace(':id',id);

        // $.ajax({
        //     type:"GET",
        //     url:url,
        //     success: function(response)
        //     {
        //         $("#content-modal").html("");
        //         $("#content-modal").html(response);
        //     }
        // })
    }

</script>
@endpush
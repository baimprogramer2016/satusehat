@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Jadwal (Kirim ke Satu Sehat)</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">

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
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Cron</th>
                                    <th>Command</th>
                                    <th>Status</th>
                                    <th>Updated At</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_jadwal as $item_jadwal)
                                <tr>
                                    <td>{{ $item_jadwal->kode }}</td>
                                    <td>{{ $item_jadwal->name }}</td>
                                    <td>{{ $item_jadwal->cron }}</td>
                                    <td>{{ $item_jadwal->command }}</td>
                                    <td class='{{ ($item_jadwal->status==1) ? ' text-success' : 'text-danger' }}'>{{
                                        ($item_jadwal->status == 1) ? 'Aktif' : 'Tidak Aktif' }}
                                    </td>
                                    <td>{{ $item_jadwal->updated_at }}</td>
                                    <td>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">

                                                    <li><a onClick="return modalUbah('{{ Crypt::encrypt($item_jadwal->id) }}')"
                                                            href="#file-upload" data-toggle="modal"><em
                                                                class="icon ni ni-edit"></em><span>Atur
                                                                Jadwal</span></a>
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
    <div class="modal-dialog modal-md  modal-dialog-top" role="document">
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
        stateSave: true,
          language : {
                sLengthMenu: "Show _MENU_"
            },
        });


    function modalUbah(id)
    {
        loadingProcess(); //dari custom.js
        var url     = '{{ route("jadwal-ubah", ":id") }}';
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
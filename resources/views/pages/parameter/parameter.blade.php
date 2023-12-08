@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Paramater</h3>
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
                        <li><a href="#file-upload" onClick="modalUbah()"
                                class="btn btn-white btn-dim btn-outline-primary" data-toggle="modal"><em
                                    class="icon ni ni-edit"></em><span>Ubah</span></a>
                        </li>
                        <li><a href="#file-upload" onClick="modalLihat()" data-toggle="modal"
                                class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-eye"></em><span>Lihat</span></a>
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
                <div class=" nk-tb-list">
                    <div class="nk-tb-item nk-tb-head">
                        <div class="nk-tb-col border"><span>ID</span></div>
                        <div class="nk-tb-col border tb-col-sm"><span>Auth URL</span></div>
                        <div class="nk-tb-col border tb-col-sm"><span>Base URL</span></div>
                        <div class="nk-tb-col border tb-col-sm"><span>Concent URL</span></div>
                        <div class="nk-tb-col border tb-col-sm"><span>HIT URL</span></div>
                        <div class="nk-tb-col border tb-col-sm"><span>Updated</span></div>
                    </div>
                    <div class="nk-tb-item">
                        <div class="nk-tb-col border">
                            <div class="align-center">

                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->organization_id }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="nk-tb-col tb-col-sm border">
                            <div class="user-card">
                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->auth_url }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="nk-tb-col tb-col-sm border">
                            <div class="user-card">
                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->base_url }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="nk-tb-col tb-col-sm border">
                            <div class="user-card">
                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->consent_url }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="nk-tb-col tb-col-sm border">
                            <div class="user-card">
                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->generate_token_url }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="nk-tb-col tb-col-sm border">
                            <div class="user-card">
                                <div class="user-name">
                                    <span class="tb-lead">{{ $data_parameter->updated_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
    function modalUbah()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('parameter-ubah') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }
    function modalLihat()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('parameter-lihat') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }



</script>
@endpush
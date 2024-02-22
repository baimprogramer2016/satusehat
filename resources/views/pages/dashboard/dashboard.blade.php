@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Dashboard</h3>
            <div class="nk-block-des text-soft">
                {{-- <p>Monitor Pengiriman</p> --}}
            </div>
        </div><!-- .nk-block-head-content -->


        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-download-cloud"></em><span>Panduan</span></a>
                        </li>
                        <li><a href="#file-upload" onClick="modalLaporan()" data-toggle="modal"
                                class="btn btn-white btn-dim btn-outline-success"><em
                                    class="icon ni ni-reports"></em><span>Laporan</span></a>
                        </li>
                        {{-- <li class="nk-block-tools-opt">
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em
                                        class="icon ni ni-plus"></em></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><em class="icon ni ni-user-add-fill"></em><span>Add
                                                    User</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-coin-alt-fill"></em><span>Add
                                                    Order</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-note-add-fill-c"></em><span>Add
                                                    Page</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li> --}}
                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">
        <div class="col-md-6">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-0">
                        <div class="card-title">
                            <h6 class="subtitle">Total Tahun ini</h6>
                        </div>

                    </div>
                    <div class="card-amount">
                        <span class="amount"> {{ number_format($data_dashboard['data_current_year'] ?? 0) }} <span
                                class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span>
                        --}}
                    </div>
                    <div class="invest-data mt-3">
                        <div class="invest-data-amount g-2 text-center">
                            <div class="invest-data-history bg-success  rounded-lg">
                                <div class="title text-white">Berhasil Terkirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_current_year_success'] ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history bg-danger  rounded-lg">
                                <div class="title  text-white">Gagal Terkirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_current_year_failed'] ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history bg-warning  rounded-lg">
                                <div class="title  text-white">Belum Dikirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_current_year_waiting'] ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-md-6">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-0">
                        <div class="card-title">
                            <h6 class="subtitle">Total ke seluruhan</h6>
                        </div>

                    </div>
                    <div class="card-amount">
                        <span class="amount"> {{ number_format($data_dashboard['data_year_all'] ?? 0) }} <span
                                class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span>
                        --}}
                    </div>
                    <div class="invest-data mt-3">
                        <div class="invest-data-amount g-2 text-center">
                            <div class="invest-data-history bg-success rounded-lg">
                                <div class="title  text-white">Berhasil Terkirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_year_success_all']
                                    ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history bg-danger  rounded-lg">
                                <div class="title  text-white">Gagal Terkirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_year_failed_all'] ??
                                    0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history bg-warning  rounded-lg">
                                <div class="title  text-white">Belum Dikirim</div>
                                <div class="amount  text-white"> {{
                                    number_format($data_dashboard['data_year_waiting_all']
                                    ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->

        {{-- <div class="col-md-12 col-xxl-12">
            <div class="card card-bordered card-full">
                <div class="card-inner d-flex flex-column h-100">
                    <div class="card-title-group mb-3">
                        <div class="card-title">
                            <h6 class="title">Guide</h6>

                        </div>

                    </div>
                    <div class="progress-wrap w-100">
                        <iframe src="{{ asset('uploads/guide.pdf') }}" width="100%" height="600"></iframe>
                    </div>
                    <div class="invest-top-ck mt-auto">
                        <canvas class="iv-plan-purchase" id="planPurchase"></canvas>
                    </div>
                </div>
            </div>
        </div><!-- .col --> --}}
        <div class="col-md-12 col-xxl-12">
            <div class="card card-bordered card-full">
                <div class="card-inner d-flex flex-column h-100">
                    <div class="card-title-group mb-3">
                        <div class="card-title">
                            <h6 class="title">Resource Data</h6>

                        </div>
                        {{-- <div class="card-tools mt-n4 mr-n1">
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em
                                        class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><span>Minggu ini</span></a></li>
                                        <li><a href="#" class="active"><span>Bulan ini</span></a></li>
                                        <li><a href="#"><span>Tahun ini</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="progress-list gy-3">
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Encounter</div>
                                <div class="progress-amount">{{ ROUND($data_progress['encounter_persen']) }}%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar  bg-success"
                                    data-progress="{{ ROUND($data_progress['encounter_persen']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Condition</div>
                                <div class="progress-amount">{{ ROUND($data_progress['condition_persen']) }}%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar  bg-primary"
                                    data-progress="{{ ROUND($data_progress['condition_persen']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Observation</div>
                                <div class="progress-amount">{{ ROUND($data_progress['observation_persen']) }}%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar  bg-warning"
                                    data-progress="{{ ROUND($data_progress['observation_persen']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Medication Request</div>
                                <div class="progress-amount">{{ ROUND($data_progress['medication_request_persen']) }}%
                                </div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar  bg-secondary"
                                    data-progress="{{ ROUND($data_progress['medication_request_persen']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Medication Dispense</div>
                                <div class="progress-amount">{{ ROUND($data_progress['medication_dispense_persen']) }}%
                                </div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar  bg-dark"
                                    data-progress="{{ ROUND($data_progress['medication_dispense_persen']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Allergy</div>
                                <div class="progress-amount">33%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-danger" data-progress="33"></div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="invest-top-ck mt-auto">
                        <canvas class="iv-plan-purchase" id="planPurchase"></canvas>
                    </div> --}}
                </div>
            </div>
        </div><!-- .col -->


    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal  modal-dialog-top" role="document">
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
    function modalLaporan()
    {
        loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            url:"{{ route('dashboard-laporan') }}",
            success: function(response)
            {
                $("#content-modal").html("");
                $("#content-modal").html(response);
            }
        })
    }

    function laporanDownload()
    {
        var resource = $("#resource").val();
        var status = $("#status").val();
        var tanggal_awal = $("#tanggal_awal").val();
        var tanggal_akhir = $("#tanggal_akhir").val();

        // loadingProcess(); //dari custom.js
        $.ajax({
            type:"GET",
            data : {
                resource : resource,
                status : status,
                tanggal_awal : tanggal_awal,
                tanggal_akhir : tanggal_akhir,
            },
            url:"{{ route('dashboard-laporan-download') }}",
            success: function(response)
            {
                // $("#content-modal").html("");
                // $("#content-modal").html(response);

                // console.log(response);
            }
        })
    }



</script>
@endpush
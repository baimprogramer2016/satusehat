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
                            <div class="invest-data-history border border-success border-5   rounded-lg">
                                <div class="title text-success font-weight-bold">Berhasil Terkirim</div>
                                <div class="amount  text-success font-weight-bold"> {{
                                    number_format($data_dashboard['data_current_year_success'] ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-danger  rounded-lg">
                                <div class="title  text-danger font-weight-bold">Gagal Terkirim</div>
                                <div class="amount  text-danger font-weight-bold"> {{
                                    number_format($data_dashboard['data_current_year_failed'] ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-warning  rounded-lg">
                                <div class="title  text-warning font-weight-bold">Belum Dikirim</div>
                                <div class="amount  text-warning font-weight-bold"> {{
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
                            <div class="invest-data-history border border-success rounded-lg">
                                <div class="title  text-success font-weight-bold">Berhasil Terkirim</div>
                                <div class="amount  text-success font-weight-bold"> {{
                                    number_format($data_dashboard['data_year_success_all']
                                    ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-danger  rounded-lg">
                                <div class="title  text-danger font-weight-bold">Gagal Terkirim</div>
                                <div class="amount  text-danger font-weight-bold"> {{
                                    number_format($data_dashboard['data_year_failed_all'] ??
                                    0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-warning  rounded-lg">
                                <div class="title  text-warning font-weight-bold">Belum Dikirim</div>
                                <div class="amount  text-warning font-weight-bold"> {{
                                    number_format($data_dashboard['data_year_waiting_all']
                                    ?? 0)
                                    }} <span class="currency currency-usd"></span></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{ number_format($data_condition['condition_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{ number_format($data_condition['condition_waiting']) ?? 0 }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_condition['condition_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Condition</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_observation['observation_success'])
                        ??
                        0 }}</span> /
                    <span class='text-secondary'>{{ number_format($data_observation['observation_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_observation['observation_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Observation</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_composition['composition_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_composition['composition_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_composition['composition_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Composition</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_procedure['procedure_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_procedure['procedure_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_procedure['procedure_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Procedure</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_medication_request['medication_request_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_medication_request['medication_request_waiting']) ?? 0 }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_medication_request['medication_request_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Medication Request</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_medication_dispense['medication_dispense_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_medication_dispense['medication_dispense_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_medication_dispense['medication_dispense_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Medication Dispense</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_service_request['service_request_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_service_request['service_request_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_service_request['service_request_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Service Request</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_specimen['specimen_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_specimen['specimen_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_specimen['specimen_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Specimen</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_observation_lab['observation_lab_success']) ??
                        0 }}</span> /
                    <span class='text-secondary'>{{
                        number_format($data_observation_lab['observation_lab_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_observation_lab['observation_lab_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Observation Lab</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_diagnostic_report['diagnostic_report_success']) ??
                        0 }} </span> /
                    <span class='text-secondary'>{{
                        number_format($data_diagnostic_report['diagnostic_report_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_diagnostic_report['diagnostic_report_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Diagnostic Report</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_allergy['allergy_success']) ??
                        0 }} </span> /
                    <span class='text-secondary'>{{
                        number_format($data_allergy['allergy_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_allergy['allergy_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Allergy</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary'>{{
                        number_format($data_prognosis['prognosis_success']) ??
                        0 }} </span> /
                    <span class='text-secondary'>{{
                        number_format($data_prognosis['prognosis_waiting']) ?? 0
                        }}</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong>{{
                        number_format($data_prognosis['prognosis_failed']) ?? 0 }} <span
                            class="currenct currency-usd"></span></strong>
                </div>
                <div class="title">Clinical</div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
        <div class="modal-dialog modal  modal-dialog-top" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md" id="content-modal">
                    {{-- Content Here --}}
                    <div class='d-flex justify-content-center' id='loading-process'>
                        <div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span>
                        </div>
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
    function runService()
    {
        $.ajax({
            type:"GET",
            url:"{{ route('dashboard-service') }}",
            success: function(response)
            {
               console.log(response);
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
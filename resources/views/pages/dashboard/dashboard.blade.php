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
                        <li><span id="auto_refresh" class="btn btn-success " onclick="refresh()"><em
                                    class="icon ni ni-clock"></em>Start Auto
                                Refresh</span>
                        </li>
                        <li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-download-cloud"></em><span>Panduan</span></a>
                        </li>
                        <li><a href="#file-upload" onClick="modalLaporan()" data-toggle="modal"
                                class="btn btn-white btn-dim btn-outline-info"><em
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
                        <span class="amount" id="encounter_year">0 <span class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span>
                        --}}
                    </div>
                    <div class="invest-data mt-3">
                        <div class="invest-data-amount g-2 text-center">
                            <div class="invest-data-history border border-success border-5   rounded-lg">
                                <div class="title text-success font-weight-bold">Berhasil Terkirim</div>
                                <div id="encounter_year_success" class="amount  text-success font-weight-bold">0 <span
                                        class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-danger  rounded-lg">
                                <div class="title  text-danger font-weight-bold">Gagal Terkirim</div>
                                <div id="encounter_year_failed" class="amount  text-danger font-weight-bold">0 <span
                                        class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-warning  rounded-lg">
                                <div class="title  text-warning font-weight-bold">Belum Dikirim</div>
                                <div id="encounter_year_waiting" class="amount  text-warning font-weight-bold">0<span
                                        class="currency currency-usd"></span></div>
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
                        <span id="encounter_all" class="amount"> 0 <span class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span>
                        --}}
                    </div>
                    <div class="invest-data mt-3">
                        <div class="invest-data-amount g-2 text-center">
                            <div class="invest-data-history border border-success rounded-lg">
                                <div class="title  text-success font-weight-bold">Berhasil Terkirim</div>
                                <div id="encounter_all_success" class="amount  text-success font-weight-bold"> 0 <span
                                        class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-danger  rounded-lg">
                                <div class="title  text-danger font-weight-bold">Gagal Terkirim</div>
                                <div id="encounter_all_failed" class="amount  text-danger font-weight-bold"> 0 <span
                                        class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history border border-warning  rounded-lg">
                                <div class="title  text-warning font-weight-bold">Belum Dikirim</div>
                                <div id="encounter_all_waiting" class="amount  text-warning font-weight-bold"> 0<span
                                        class="currency currency-usd"></span></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="condition_success">0</span> /
                    <span class='text-secondary' id="condition_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="condition_failed">0</span></strong>
                </div>
                <div class="title">Condition</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="observation_success">0</span> /
                    <span class='text-secondary' id="observation_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="observation_failed">0</span></strong>
                </div>
                <div class="title">Observation</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="composition_success">0</span> /
                    <span class='text-secondary' id="composition_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="composition_failed">0</span></strong>
                </div>
                <div class="title">Composition</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="procedure_success">0</span> /
                    <span class='text-secondary' id="procedure_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="procedure_failed">0</span></strong>
                </div>
                <div class="title">Procedure</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="medication_request_success">0</span> /
                    <span class='text-secondary' id="medication_request_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="medication_request_failed">0</span></strong>
                </div>
                <div class="title">Medication Request</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="medication_dispense_success">0</span> /
                    <span class='text-secondary' id="medication_dispense_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="medication_dispense_failed">0</span></strong>
                </div>
                <div class="title">Medication Dispense</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="service_request_success">0</span> /
                    <span class='text-secondary' id="service_request_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="service_request_failed">0</span></strong>
                </div>
                <div class="title">Service Request</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="specimen_success">0</span> /
                    <span class='text-secondary' id="specimen_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="specimen_failed">0</span></strong>
                </div>
                <div class="title">Specimen</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="observation_lab_success">0</span> /
                    <span class='text-secondary' id="observation_lab_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="observation_lab_failed">0</span></strong>
                </div>
                <div class="title">Observation Lab</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="diagnostic_report_success">0</span> /
                    <span class='text-secondary' id="diagnostic_report_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="diagnostic_report_failed">0</span></strong>
                </div>
                <div class="title">Diagnostic Report</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="allergy_success">0</span> /
                    <span class='text-secondary' id="allergy_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="allergy_failed">0</span></strong>
                </div>
                <div class="title">Allergy</div>
            </div>
        </div>
        <div class="col-md-4 ">
            <div class="nk-order-ovwg-data bg-white border border-opacity-5">
                <div class="amount"><span class='text-primary' id="prognosis_success">0</span> /
                    <span class='text-secondary' id="prognosis_waiting">0</span>
                    <small class="currenct currency-usd"></small>
                </div>
                <div class="info text-danger">Gagal Kirim : <strong> <span class="currenct currency-usd"
                            id="prognosis_failed">0</span></strong>
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

    function fetchData()
    {
        $.ajax({
            type:"GET",
            url:"{{ route('dashboard-refresh') }}",
            success: function(response)
            {
                // console.log(response.data_encounter);
                var encounter = response.data_encounter;
                var condition = response.data_condition;
                var observation = response.data_observation;
                var procedure = response.data_procedure;
                var composition = response.data_composition;
                var medication_request = response.data_medication_request;
                var medication_dispense = response.data_medication_dispense;
                var service_request = response.data_service_request;
                var specimen = response.data_specimen;
                var observation_lab = response.data_observation_lab;
                var diagnostic_report = response.data_diagnostic_report;
                var allergy = response.data_allergy;
                var prognosis = response.data_prognosis;

                $("#encounter_year").html(encounter.data_current_year.toLocaleString('de-DE')+' Encounter');
                $("#encounter_year_success").html(encounter.data_current_year_success.toLocaleString('de-DE'));
                $("#encounter_year_failed").html(encounter.data_current_year_failed.toLocaleString('de-DE'));
                $("#encounter_year_waiting").html(encounter.data_current_year_waiting.toLocaleString('de-DE'));
                $("#encounter_all").html(encounter.data_year_all.toLocaleString('de-DE')+' Encounter');
                $("#encounter_all_success").html(encounter.data_year_success_all.toLocaleString('de-DE'));
                $("#encounter_all_failed").html(encounter.data_year_failed_all.toLocaleString('de-DE'));
                $("#encounter_all_waiting").html(encounter.data_year_waiting_all.toLocaleString('de-DE'));
                $("#condition_success").html(condition.condition_success.toLocaleString('de-DE'));
                $("#condition_failed").html(condition.condition_failed.toLocaleString('de-DE'));
                $("#condition_waiting").html(condition.condition_waiting.toLocaleString('de-DE'));
                $("#observation_success").html(observation.observation_success.toLocaleString('de-DE'));
                $("#observation_failed").html(observation.observation_failed.toLocaleString('de-DE'));
                $("#observation_waiting").html(observation.observation_waiting.toLocaleString('de-DE'));
                $("#procedure_success").html(procedure.procedure_success.toLocaleString('de-DE'));
                $("#procedure_failed").html(procedure.procedure_failed.toLocaleString('de-DE'));
                $("#procedure_waiting").html(procedure.procedure_waiting.toLocaleString('de-DE'));
                $("#composition_success").html(composition.composition_success.toLocaleString('de-DE'));
                $("#composition_failed").html(composition.composition_failed.toLocaleString('de-DE'));
                $("#composition_waiting").html(composition.composition_waiting.toLocaleString('de-DE'));
                $("#medication_request_success").html(medication_request.medication_request_success.toLocaleString('de-DE'));
                $("#medication_request_failed").html(medication_request.medication_request_failed.toLocaleString('de-DE'));
                $("#medication_request_waiting").html(medication_request.medication_request_waiting.toLocaleString('de-DE'));
                $("#medication_dispense_success").html(medication_dispense.medication_dispense_success.toLocaleString('de-DE'));
                $("#medication_dispense_failed").html(medication_dispense.medication_dispense_failed.toLocaleString('de-DE'));
                $("#medication_dispense_waiting").html(medication_dispense.medication_dispense_waiting.toLocaleString('de-DE'));
                $("#service_request_success").html(service_request.service_request_success.toLocaleString('de-DE'));
                $("#service_request_failed").html(service_request.service_request_failed.toLocaleString('de-DE'));
                $("#service_request_waiting").html(service_request.service_request_waiting.toLocaleString('de-DE'));
                $("#specimen_success").html(specimen.specimen_success.toLocaleString('de-DE'));
                $("#specimen_failed").html(specimen.specimen_failed.toLocaleString('de-DE'));
                $("#specimen_waiting").html(specimen.specimen_waiting.toLocaleString('de-DE'));
                $("#observation_lab_success").html(observation_lab.observation_lab_success.toLocaleString('de-DE'));
                $("#observation_lab_failed").html(observation_lab.observation_lab_failed.toLocaleString('de-DE'));
                $("#observation_lab_waiting").html(observation_lab.observation_lab_waiting.toLocaleString('de-DE'));
                $("#diagnostic_report_success").html(diagnostic_report.diagnostic_report_success.toLocaleString('de-DE'));
                $("#diagnostic_report_failed").html(diagnostic_report.diagnostic_report_failed.toLocaleString('de-DE'));
                $("#diagnostic_report_waiting").html(diagnostic_report.diagnostic_report_waiting.toLocaleString('de-DE'));
                $("#allergy_success").html(allergy.allergy_success.toLocaleString('de-DE'));
                $("#allergy_failed").html(allergy.allergy_failed.toLocaleString('de-DE'));
                $("#allergy_waiting").html(allergy.allergy_waiting.toLocaleString('de-DE'));
                $("#prognosis_success").html(prognosis.prognosis_success.toLocaleString('de-DE'));
                $("#prognosis_failed").html(prognosis.prognosis_failed.toLocaleString('de-DE'));
                $("#prognosis_waiting").html(prognosis.prognosis_waiting.toLocaleString('de-DE'));



            }
        })
    }
    fetchData();

    function refresh()
    {
        var text_button = $("#auto_refresh").html().replace(/\s+/g, '');
        let text = $('<div>').html(text_button).text();
        // let text = $tempDiv.find('span').text();

        if(text === 'StartAutoRefresh')
        {
            $("#auto_refresh").html("<em class='icon ni ni-clock'></em>  Stop Auto Refresh");
            $("#auto_refresh").first().removeClass('btn-success');
            $("#auto_refresh").first().addClass('btn-danger');

            setInterval(function() {
                //get ulang lagi
                var text_button = $("#auto_refresh").html().replace(/\s+/g, '');
                let text = $('<div>').html(text_button).text();
                if (text === 'StopAutoRefresh') {
                    fetchData();
                }
            }, 10000);
        }
        else if(text === 'StopAutoRefresh')
        {
            $("#auto_refresh").html("<em class='icon ni ni-clock'></em> Start Auto Refresh");
            $("#auto_refresh").first().removeClass('btn-danger');
            $("#auto_refresh").first().addClass('btn-success');
        }

    }



    </script>
    @endpush
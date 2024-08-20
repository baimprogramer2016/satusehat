<?php

namespace App\Jobs;

use App\Models\Encounter;
use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Illuminate\Support\Facades\Log;
use Throwable;


class BundleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiTrait, JsonTrait;

    /**
     * Create a new job instance.
     */
    protected $bundle_repo,
        $condition_repo,
        $observation_repo,
        $procedure_repo,
        $composition_repo,
        $medication_request_repo,
        $medication_dispense_repo,
        $service_request_repo,
        $specimen_repo,
        $observation_lab_repo,
        $diagnostic_report_repo,
        $service_request_radiology_repo,
        $allergy_repo,
        $prognosis_repo,
        $rencana_tindak_lanjut_repo;
    protected $parameter_repo;
    protected $job_id;
    protected $job_logs_repo;
    public $timeout = 0; # artinya 120 detik / 2 menit
    public function __construct(
        $bundle_repo,
        $condition_repo,
        $parameter_repo,
        $job_logs_repo,
        $job_id, #job_id untuk id unik job logs
        $observation_repo,
        $procedure_repo,
        $composition_repo,
        $medication_request_repo,
        $medication_dispense_repo,
        $service_request_repo,
        $specimen_repo,
        $observation_lab_repo,
        $diagnostic_report_repo,
        $service_request_radiology_repo,
        $allergy_repo,
        $prognosis_repo,
        $rencana_tindak_lanjut_repo,
    ) {
        $this->bundle_repo = $bundle_repo; #data yang akan dieksekusi
        $this->condition_repo = $condition_repo;
        $this->parameter_repo = $parameter_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
        $this->observation_repo = $observation_repo;
        $this->procedure_repo = $procedure_repo;
        $this->composition_repo = $composition_repo;
        $this->medication_request_repo = $medication_request_repo;
        $this->medication_dispense_repo = $medication_dispense_repo;
        $this->service_request_repo = $service_request_repo;
        $this->specimen_repo = $specimen_repo;
        $this->observation_lab_repo = $observation_lab_repo;
        $this->diagnostic_report_repo = $diagnostic_report_repo;
        $this->service_request_radiology_repo = $service_request_radiology_repo;
        $this->allergy_repo = $allergy_repo;
        $this->prognosis_repo = $prognosis_repo;
        $this->rencana_tindak_lanjut_repo = $rencana_tindak_lanjut_repo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # ambil data 100 Record sekali eksekusi
        $data_bundle = $this->bundle_repo->getDataBundleReadyJob();

        if ($data_bundle->count() > 0) {

            # parameter body json
            $param_bundle['parameter'] = $this->parameter_repo->getDataParameterFirst();

            #response default
            # INTI PERULANGAN PER REGNO
            foreach ($data_bundle as $item_bundle) {
                $res['satusehat_id'] = null;
                $res['satusehat_send'] = 0;
                $res['satusehat_statuscode'] = 500;
                $res['satusehat_date'] = $this->currentNow();

                # response default
                $res['id'] = $item_bundle->id; # id encounter
                $res['encounter_original_code'] = $item_bundle->original_code; # regno encounter

                #parameter body json per item
                $param_bundle['bundle'] = $item_bundle;
                $param_bundle['observation'] = $this->observation_repo->getDataObservationBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['procedure'] = $this->procedure_repo->getDataProcedureBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['composition'] = $this->composition_repo->getDataCompositionBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['medication_request'] = $this->medication_request_repo->getDataMedicationRequestBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['medication_dispense'] = $this->medication_dispense_repo->getDataMedicationDispenseBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['service_request'] = $this->service_request_repo->getDataServiceRequestBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['specimen'] = $this->specimen_repo->getDataSpecimenBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['observation_lab'] = $this->observation_lab_repo->getDataObservationLabBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['diagnostic_report'] = $this->diagnostic_report_repo->getDataDiagnosticReportBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['service_request_radiology'] = $this->service_request_radiology_repo->getDataServiceRequestRadiologyBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['allergy'] = $this->allergy_repo->getDataAllergyBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['prognosis'] = $this->prognosis_repo->getDataPrognosisBundleByOriginalCode($item_bundle->original_code);
                $param_bundle['rencana_tindak_lanjut'] = $this->rencana_tindak_lanjut_repo->getDataRencanaTindakLanjutBundleByOriginalCode($item_bundle->original_code);

                # API POST Bundle
                $payload_bundle = $this->bodyBundle($param_bundle); // data bundle



                $response = $this->post_general_ss('', $payload_bundle);
                $body_parse = json_decode($response->body());

                # response default - replace
                $res['satusehat_response'] = $response;
                $res['satusehat_request'] = $payload_bundle;

                # hasil response diolah
                if ($response->successful()) {
                    if (count($body_parse->entry) > 0) {

                        # response default
                        $res['satusehat_send'] = 1;
                        $res['satusehat_statuscode'] = 200;
                        $rank = 1;
                        foreach ($body_parse->entry as $item_response) {

                            # update status dari response masing2 punya response , encounter , condition dll
                            if ($item_response->response->resourceType == 'Encounter') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->bundle_repo->updateDataBundleEncounterJob($res);
                            }

                            # update status condition
                            if ($item_response->response->resourceType == 'Condition') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->condition_repo->updateDataBundleConditionJob($res, $rank);
                                $rank++;
                            }

                            # update status observation
                            if ($item_response->response->resourceType == 'Observation') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->observation_repo->updateDataBundleObservationJob($res);
                            }

                            # update status procedure
                            if ($item_response->response->resourceType == 'Procedure') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->procedure_repo->updateDataBundleProcedureJob($res);
                            }

                            # update status compositoin
                            if ($item_response->response->resourceType == 'Composition') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->composition_repo->updateDataBundleCompositionJob($res);
                            }

                            # update status medication
                            if ($item_response->response->resourceType == 'Medication') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->medication_request_repo->updateDataBundleMedicationJob($res);
                            }
                            # update status medication request
                            if ($item_response->response->resourceType == 'MedicationRequest') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->medication_request_repo->updateDataBundleMedicationRequestJob($res);
                            }

                            # update status medication dispense
                            if ($item_response->response->resourceType == 'MedicationDispense') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->medication_dispense_repo->updateDataBundleMedicationDispenseJob($res);
                            }
                            # update status service Request
                            if ($item_response->response->resourceType == 'ServiceRequest') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->service_request_repo->updateDataBundleServiceRequestJob($res);
                            }
                            # update status specimen
                            if ($item_response->response->resourceType == 'Specimen') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->specimen_repo->updateDataBundleSpecimenJob($res);
                            }
                            # update status Diagnostic Report
                            if ($item_response->response->resourceType == 'DiagnosticReport') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->diagnostic_report_repo->updateDataBundleDiagnosticReportJob($res);
                            }
                            # update status dari response
                            # update status Diagnostic Report
                            if ($item_response->response->resourceType == 'AllergyIntolerance') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->allergy_repo->updateDataBundleAllergyJob($res);
                            }
                            if ($item_response->response->resourceType == 'ClinicalImpression') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->prognosis_repo->updateDataBundlePrognosisJob($res);
                            }
                            if ($item_response->response->resourceType == 'CarePlan') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->rencana_tindak_lanjut_repo->updateDataBundleRencanaTindakLanjutJob($res);
                            }
                            # update status dari response
                        }
                    }
                } else {
                    $this->bundle_repo->updateDataBundleEncounterJob($res);
                }
            }
            # membuat Update status Completed end job pada job Log

            $param_end['id'] = $this->job_id;
            $param_end['end'] =  $this->currentNow();
            $param_end['status'] =  'Completed';
            $param_end['error_message'] =  null;
            $this->job_logs_repo->updateJobLogsEnd($param_end);
        }
    }

    public function failed(Throwable $e)
    {
        // Called when the job is failing...
        $param_end['id'] = $this->job_id;
        $param_end['end'] =  $this->currentNow();
        $param_end['status'] =  'Failed';
        $param_end['error_message'] = $e->getMessage();
        $this->job_logs_repo->updateJobLogsEnd($param_end);
    }
}

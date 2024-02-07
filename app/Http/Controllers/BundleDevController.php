<?php

namespace App\Http\Controllers;

use App\Jobs\BundleJob;
use App\Repositories\Composition\CompositionInterface;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\DiagnosticReport\DiagnosticReportInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Medication\MedicationInterface;
use App\Repositories\MedicationDispense\MedicationDispenseInterface;
use App\Repositories\MedicationRequest\MedicationRequestInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\ObservationLab\ObservationLabInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\Procedure\ProcedureInterface;
use App\Repositories\ServiceRequest\ServiceRequestInterface;
use App\Repositories\Specimen\SpecimenInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Carbon\Carbon;
use Exception;
use Throwable;

class BundleDevController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;


    public $job_logs_repo;
    public $bundle_repo,
        $condition_repo,
        $observation_repo,
        $procedure_repo,
        $composition_repo,
        $medication_request_repo,
        $medication_dispense_repo,
        $service_request_repo,
        $speciment_repo,
        $observation_lab_repo,
        $diagnostic_report_repo;
    public $parameter_repo;
    protected $job_id = 0;
    public function __construct(
        JobLogsInterface $jobLogsInterface,
        EncounterInterface $encounterInterface,
        ConditionInterface $conditionInterface,
        ParameterInterface $parameterInterface,
        ObservationInterface $observationInterface,
        ProcedureInterface $procedureInterface,
        CompositionInterface $compositionInterface,
        MedicationRequestInterface $medicationRequestInterface,
        MedicationDispenseInterface $medicationDispenseInterface,
        ServiceRequestInterface $serviceRequestInterface,
        SpecimenInterface $specimenInterface,
        ObservationLabInterface $observationLabInterface,
        DiagnosticReportInterface $diagnosticReportInterface
    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->bundle_repo = $encounterInterface;
        $this->condition_repo = $conditionInterface;
        $this->parameter_repo = $parameterInterface;
        $this->observation_repo = $observationInterface;
        $this->procedure_repo = $procedureInterface;
        $this->composition_repo = $compositionInterface;
        $this->medication_request_repo = $medicationRequestInterface;
        $this->medication_dispense_repo = $medicationDispenseInterface;
        $this->service_request_repo = $serviceRequestInterface;
        $this->speciment_repo = $specimenInterface;
        $this->observation_lab_repo = $observationLabInterface;
        $this->diagnostic_report_repo = $diagnosticReportInterface;
    }
    public function runJob(Request $request)
    {
        try {

            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = config('constan.job_name.bundle'); //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed


            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->bundle_repo->getDataBundleReadyJob()->count() > 0) {


                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    // $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    // $this->job_id = $job_report->id;
                    // BundleJob::dispatch(
                    //     $this->bundle_repo,
                    //     $this->condition_repo,
                    //     $this->parameter_repo,
                    //     $this->job_logs_repo,
                    //     $this->job_id
                    // );

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
                            $param_bundle['observation'] = $this->observation_repo->getDataObservationByOriginalCode($item_bundle->original_code);
                            $param_bundle['procedure'] = $this->procedure_repo->getDataProcedureByOriginalCode($item_bundle->original_code);
                            $param_bundle['composition'] = $this->composition_repo->getDataCompositionByOriginalCode($item_bundle->original_code);
                            $param_bundle['medication_request'] = $this->medication_request_repo->getDataMedicationRequestByOriginalCodeReady($item_bundle->original_code);
                            $param_bundle['medication_dispense'] = $this->medication_dispense_repo->getDataMedicationDispenseByOriginalCode($item_bundle->original_code);
                            $param_bundle['service_request'] = $this->service_request_repo->getDataServiceRequestByOriginalCode($item_bundle->original_code);
                            $param_bundle['specimen'] = $this->speciment_repo->getDataSpecimenByOriginalCode($item_bundle->original_code);
                            $param_bundle['observation_lab'] = $this->observation_lab_repo->getDataObservationLabByOriginalCode($item_bundle->original_code);
                            $param_bundle['diagnostic_report'] = $this->diagnostic_report_repo->getDataDiagnosticReportByOriginalCode($item_bundle->original_code);


                            # API POST Bundle
                            $payload_bundle = $this->bodyBundle($param_bundle); // ada dua parameter


                            return $payload_bundle;
                        }
                        # membuat Update status Completed end job pada job Log
                    }
                }
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\BundleJob;
use App\Repositories\Allergy\AllergyInterface;
use App\Repositories\Composition\CompositionInterface;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\DiagnosticReport\DiagnosticReportInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\Jadwal\JadwalInterface;
use App\Repositories\Procedure\ProcedureInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\MedicationDispense\MedicationDispenseInterface;
use App\Repositories\MedicationRequest\MedicationRequestInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\ObservationLab\ObservationLabInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\Prognosis\PrognosisInterface;
use App\Repositories\ServiceRequest\ServiceRequestInterface;
use App\Repositories\ServiceRequestRadiology\ServiceRequestRadiologyInterface;
use App\Repositories\Specimen\SpecimenInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Carbon\Carbon;
use Exception;
use Throwable;

class BundleController extends Controller
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
        $specimen_repo,
        $observation_lab_repo,
        $diagnostic_report_repo,
        $service_request_radiology_repo,
        $allergy_repo,
        $prognosis_repo;

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
        DiagnosticReportInterface $diagnosticReportInterface,
        ServiceRequestRadiologyInterface $serviceRequestRadiologyInterface,
        AllergyInterface $allergyInterface,
        PrognosisInterface $prognosisInterface
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
        $this->specimen_repo = $specimenInterface;
        $this->observation_lab_repo = $observationLabInterface;
        $this->observation_lab_repo = $observationLabInterface;
        $this->diagnostic_report_repo = $diagnosticReportInterface;
        $this->service_request_radiology_repo = $serviceRequestRadiologyInterface;
        $this->allergy_repo = $allergyInterface;
        $this->prognosis_repo = $prognosisInterface;
    }
    public function runJob(Request $request, $param_id_jadwal)
    {
        try {

            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //config('constan.job_name.bundle'); //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed


            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->bundle_repo->getDataBundleReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    BundleJob::dispatch(
                        $this->bundle_repo,
                        $this->condition_repo,
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->observation_repo,
                        $this->procedure_repo,
                        $this->composition_repo,
                        $this->medication_request_repo,
                        $this->medication_dispense_repo,
                        $this->service_request_repo,
                        $this->specimen_repo,
                        $this->observation_lab_repo,
                        $this->diagnostic_report_repo,
                        $this->service_request_radiology_repo,
                        $this->allergy_repo,
                        $this->prognosis_repo,
                    );
                }
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

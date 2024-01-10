<?php

namespace App\Http\Controllers;

use App\Jobs\BundleJob;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\Procedure\ProcedureInterface;
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
    public $bundle_repo, $condition_repo, $observation_repo, $procedure_repo;
    public $parameter_repo;
    protected $job_id = 0;
    public function __construct(
        JobLogsInterface $jobLogsInterface,
        EncounterInterface $encounterInterface,
        ConditionInterface $conditionInterface,
        ParameterInterface $parameterInterface,
        ObservationInterface $observationInterface,
        ProcedureInterface $procedureInterface
    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->bundle_repo = $encounterInterface;
        $this->condition_repo = $conditionInterface;
        $this->parameter_repo = $parameterInterface;
        $this->observation_repo = $observationInterface;
        $this->procedure_repo = $procedureInterface;
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

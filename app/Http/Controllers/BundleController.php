<?php

namespace App\Http\Controllers;

use App\Jobs\BundleJob;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Parameter\ParameterInterface;
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
    public $bundle_repo, $condition_repo;
    public $parameter_repo;
    protected $job_id = 0;
    public function __construct(
        JobLogsInterface $jobLogsInterface,
        EncounterInterface $encounterInterface,
        ConditionInterface $conditionInterface,
        ParameterInterface $parameterInterface
    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->bundle_repo = $encounterInterface;
        $this->condition_repo = $conditionInterface;
        $this->parameter_repo = $parameterInterface;
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
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    BundleJob::dispatch(
                        $this->bundle_repo,
                        $this->condition_repo,
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id
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

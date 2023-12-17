<?php

namespace App\Http\Controllers;

use App\Repositories\JobLogs\JobLogsInterface;
use Illuminate\Http\Request;

class JobLogsController extends Controller
{

    protected $job_logs_repo;

    public function __construct(JobLogsInterface $jobLogsInterface)
    {
        $this->job_logs_repo = $jobLogsInterface;
    }
    public function index()
    {
        return view("pages.joblogs.job-logs", [
            "data_job_logs" => $this->job_logs_repo->getDataJobLogs(),
        ]);
    }
}

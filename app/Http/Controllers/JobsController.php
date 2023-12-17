<?php

namespace App\Http\Controllers;

use App\Repositories\Jobs\JobsInterface;
use Illuminate\Http\Request;

class JobsController extends Controller
{

    protected $jobs_repo;

    public function __construct(JobsInterface $jobsInterface)
    {
        $this->jobs_repo = $jobsInterface;
    }
    public function index()
    {
        return view("pages.jobs.jobs", [
            "data_job" => $this->jobs_repo->getDataJobs(),
        ]);
    }
}

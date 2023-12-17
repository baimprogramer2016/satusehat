<?php

namespace App\Repositories\Jobs;

use App\Models\Jobs;

class JobsRepository implements JobsInterface
{
    private $model;
    public function __construct(Jobs $job)
    {
        $this->model = $job;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataJobs()
    {
        return $this->model->orderBy('id', 'ASC')->get();
    }
}

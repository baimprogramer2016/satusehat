<?php

namespace App\Repositories\JobLogs;

use App\Models\JobLogs;
use App\Models\Queue;
use App\Repositories\JobLogs\JobLogsInterface;

class JobLogsRepository implements JobLogsInterface
{
    private $model;
    public function __construct(JobLogs $jobLogs)
    {
        $this->model = $jobLogs;
    }

    public function getDataJobLogs()
    {
        return $this->model->orderBy('updated_at', 'desc')->get();
    }

    public function insertJobLogsStart($param = [])
    {
        $result = $this->model->create([
            'kode' => $param['id'],
            'status' => $param['status'],
            'action' => $param['action'],
            'start_date' => $param['start']
        ]);
        return $result;
    }

    public function updateJobLogsEnd($param = [])
    {
        $result = $this->model->find($param['id']);
        $result->status = $param['status'];
        $result->end_date = $param['end'];
        $result->error_message = $param['error_message'];
        $result->update();

        return $result;
    }

    public function getDataJobLogAlreadyRun($kode)
    {
        return $this->model->where('kode', $kode)->where('status', 'Process')->get()->count();
    }
}

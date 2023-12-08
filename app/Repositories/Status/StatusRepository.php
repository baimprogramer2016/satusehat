<?php

namespace App\Repositories\Status;

use App\Models\Status;

class StatusRepository implements StatusInterface
{

    private $model;
    public function __construct()
    {
        $this->model = new Status();
    }
    public function getDataStatus()
    {
        return $this->model->get();
    }

    public function getDataStatusNotSend()
    {
        return $this->model->whereIn('status', [0, 4])->get();
    }
}

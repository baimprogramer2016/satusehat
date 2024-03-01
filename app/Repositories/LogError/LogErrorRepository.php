<?php

namespace App\Repositories\LogError;

use App\Models\LogError;

class LogErrorRepository implements LogErrorInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new LogError();
    }
    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }
}

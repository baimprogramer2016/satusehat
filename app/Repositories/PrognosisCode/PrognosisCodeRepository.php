<?php

namespace App\Repositories\PrognosisCode;

use App\Models\PrognosisCode;


class PrognosisCodeRepository implements PrognosisCodeInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new PrognosisCode();
    }
    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }

    // public function insertKfa($request = [])
    // {
    //     return $this->model->insert($request);
    // }
}

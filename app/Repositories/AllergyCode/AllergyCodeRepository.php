<?php

namespace App\Repositories\AllergyCode;

use App\Models\AllergyCode;


class AllergyCodeRepository implements AllergyCodeInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new AllergyCode();
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

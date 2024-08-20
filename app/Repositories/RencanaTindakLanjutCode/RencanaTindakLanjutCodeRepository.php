<?php

namespace App\Repositories\RencanaTindakLanjutCode;

use App\Models\RencanaTindakLanjutCode;


class RencanaTindakLanjutCodeRepository implements RencanaTindakLanjutCodeInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new RencanaTindakLanjutCode();
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

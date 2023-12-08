<?php

namespace App\Repositories\PhysicalType;

use App\Models\PhysicalType;

class PhysicalTypeRepository implements PhysicalTypeInterface
{
    private $model;
    public function __construct()
    {
        $this->model = new PhysicalType();
    }

    public function getDataPhysicalType()
    {
        return $this->model->get();
    }
    public function getDataPhysicalTypeCode($code)
    {
        return $this->model->select('display')->where('code', $code)->first()->display;
    }
}

<?php

namespace App\Repositories\Medication;

use App\Models\Medication;

class MedicationRepository implements MedicationInterface
{
    private $model;

    public function __construct(Medication $medication)
    {
        $this->model = $medication;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getMedicationId($id)
    {
        return $this->model->where('id', $id)->first();
    }
}

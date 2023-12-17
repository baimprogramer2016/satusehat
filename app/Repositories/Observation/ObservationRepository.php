<?php

namespace App\Repositories\Observation;

use App\Models\Observation;

class ObservationRepository implements ObservationInterface
{
    private $model;


    public function __construct(Observation $observationModel)
    {
        $this->model = $observationModel;
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    # untuk mendapatkan keseluruhan data
    public function getDataObservationByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }
}

<?php

namespace App\Repositories\ObservationLab;

use App\Models\Observation;
use App\Models\ObservationLab;
use Carbon\Carbon;

class ObservationLabRepository implements ObservationLabInterface
{
    private $model, $observation_model;
    public function __construct(
        ObservationLab $observationLabInterface,
        Observation $observationInterface
    ) {
        $this->model = $observationLabInterface;
        $this->observation_model = $observationInterface;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    //untuk observation
    public function getObservationLabQuery()
    {
        return $this->observation_model->query()->whereNotIn('type_observation', ['suhu', 'sistol', 'nadi', 'pernapasan', 'diastole']);
    }

    #ini untuk bundle
    public function getDataObservationLabByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
}

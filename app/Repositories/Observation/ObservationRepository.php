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
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleObservationJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id')
            ->orderBy('id', 'asc')
            ->first();
        if ($data->count() > 1) {
            $data->satusehat_id = $param['satusehat_id'];
            $data->satusehat_send = $param['satusehat_send'];
            $data->satusehat_date = $param['satusehat_date'];
            $data->satusehat_statuscode = $param['satusehat_statuscode'];
            $data->satusehat_request = $param['satusehat_request'];
            $data->satusehat_response = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }
}

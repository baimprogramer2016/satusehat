<?php

namespace App\Repositories\ObservationLab;

use App\Models\Observation;
use App\Models\ObservationLab;
use Carbon\Carbon;

class ObservationLabRepository implements ObservationLabInterface
{
    private $model, $observation_model;
    public function __construct(
        ObservationLab $observationLabModel,
        Observation $observationModel
    ) {
        $this->model = $observationLabModel;
        $this->observation_model = $observationModel;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    //untuk observation
    public function getObservationLabQuery()
    {
        return $this->observation_model->query()
            ->where('type_observation', 'Laboratory');
    }

    #ini untuk bundle
    public function getDataObservationLabByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }

    public function getDataObservationLabFind($uuid)
    {
        return $this->model->where('uuid_observation', $uuid)->first(); //dari table service request
    }

    public function updateStatusObservationLab($uuid, $satusehat_id, $request, $response) //update di table observation
    {
        $data = $this->observation_model->where('uuid', $uuid)
            ->update([
                'satusehat_id' => $satusehat_id,
                'satusehat_request' => $request,
                'satusehat_response' => $response,
                'satusehat_send' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
}

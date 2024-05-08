<?php

namespace App\Repositories\Observation;

use App\Models\Observation;
use Carbon\Carbon;

class ObservationRepository implements ObservationInterface
{
    private $model;


    public function __construct(Observation $observationModel)
    {
        $this->model = $observationModel;
    }

    public function getQuery()
    {
        return $this->model->query()->whereIn('type_observation', ['suhu', 'sistol', 'nadi', 'pernapasan', 'diastole']);
    }

    public function getDataObservationFind($id)
    {
        return $this->model->find($id);
    }

    # untuk mendapatkan keseluruhan data
    public function getDataObservationByOriginalCode($original_code)
    {
        return $this->model->whereIn('type_observation', ['suhu', 'sistol', 'nadi', 'pernapasan', 'diastole'])->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
    public function getDataObservationBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->whereIn('type_observation', ['suhu', 'sistol', 'nadi', 'pernapasan', 'diastole'])->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }

    public function updateDataBundleObservationJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
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


    public function updateStatusObservation($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->find($id);
        $data->satusehat_id = $satusehat_id;
        $data->satusehat_request = $request;
        $data->satusehat_response = $response;
        $data->satusehat_send = ($satusehat_id != null) ? 1 : 0;
        $data->satusehat_statuscode =  ($satusehat_id != null) ? '200' : '500';
        $data->satusehat_date = Carbon::now()->format('Y-m-d H:i:s');
        $data->update();

        return $data;
    }


    public function storeObservation($request =  [])
    {

        foreach ($request as $item) {
            $this->model->create($item);
        }

        return $this->model;
    }

    public function updateObservation($request =  [], $id)
    {
        $data = $this->model->find($id);
        $data->encounter_original_code = $request['encounter_original_code'];
        $data->effective_datetime = $request['effective_datetime'];
        $data->issued = $request['issued'];
        $data->quantity_value = $request['quantity_value'];
        $data->satusehat_send = $request['satusehat_send'];
        $data->update();

        return $data;
    }
    public function getDataObservationReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_observation.encounter_original_code', 'ss_encounter.original_code')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')
            ->where('ss_observation.satusehat_send', '!=', 1)
            ->whereNull('ss_observation.satusehat_statuscode')
            ->whereIn('ss_observation.type_observation', ['suhu', 'sistol', 'nadi', 'pernapasan', 'diastole'])
            // ->whereIn('original_code', ['A112306380'])
            ->select('ss_observation.*')
            ->get();
    }
}

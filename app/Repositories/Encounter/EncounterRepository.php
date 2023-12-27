<?php

namespace App\Repositories\Encounter;

use App\Models\Encounter;

class EncounterRepository implements EncounterInterface
{
    private $model;
    public function __construct(Encounter $encounterModel)
    {
        $this->model = $encounterModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getQuery()
    {
        return $this->model->query();
    }

    # BUNDLE
    public function getDataBundleReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->whereNull('satusehat_id')
            ->where('satusehat_send', '!=', 1)
            ->whereNull('satusehat_statuscode')
            // ->where('original_code', 'A052300156a')
            ->get();
    }



    public function updateDataBundleEncounterJob($param = [])
    {
        $data = $this->model->find($param['id']);
        $data->satusehat_id = $param['satusehat_id'];
        $data->satusehat_send = $param['satusehat_send'];
        $data->satusehat_date = $param['satusehat_date'];
        $data->satusehat_statuscode = $param['satusehat_statuscode'];
        $data->satusehat_request = $param['satusehat_request'];
        $data->satusehat_response = $param['satusehat_response'];
        $data->update();
        return $data;
    }

    # END BUNDLE
}

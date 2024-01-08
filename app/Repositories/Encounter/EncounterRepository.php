<?php

namespace App\Repositories\Encounter;

use App\Models\Encounter;
use Carbon\Carbon;

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
            // ->whereIn('original_code', ['LB2305290001', 'LB2305290002'])
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

    public function getDataEncounterFind($id)
    {
        return $this->model->find($id);
    }

    public function updateStatusEncounter($id, $satusehat_id, $request, $response)
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
}

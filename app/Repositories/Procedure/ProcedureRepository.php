<?php

namespace App\Repositories\Procedure;

use App\Models\Procedure;

class ProcedureRepository implements ProcedureInterface
{
    private $model;
    public function __construct(Procedure $ProcedureModel)
    {
        $this->model = $ProcedureModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataProcedureByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }


    public function updateDataBundleProcedureJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            // ->whereNull('satusehat_id')
            // ->orderBy('id', 'asc')
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
}

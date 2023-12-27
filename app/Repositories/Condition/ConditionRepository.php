<?php

namespace App\Repositories\Condition;

use App\Models\Condition;
use Illuminate\Support\Facades\Log;

class ConditionRepository implements ConditionInterface
{
    private $model;
    public function __construct(Condition $conditionModel)
    {
        $this->model = $conditionModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataConditionByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }


    public function updateDataBundleConditionJob($param = [], $rank)
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->where('rank', $rank)->first();
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

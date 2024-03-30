<?php

namespace App\Repositories\Condition;

use App\Models\Condition;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Traits\GeneralTrait;

class ConditionRepository implements ConditionInterface
{

    use GeneralTrait;
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

    public function getDataConditionFind($id)
    {
        return $this->model->find($id);
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


    public function updateStatusCondition($id, $satusehat_id, $request, $response)
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


    public function storeCondition($request =  [])
    {

        foreach ($request as $item) {
            $this->model->create($item);
        }

        return $this->model;
    }
}

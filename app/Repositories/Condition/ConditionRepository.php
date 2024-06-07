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


    public function getQuery($request = [])
    {
        $q = $this->model->query();

        //FILTER
        $q->when($request['status_kirim'] != '', function ($query) use ($request) {
            switch ($request['status_kirim']) {
                case 'waiting':
                    $query->whereNull('satusehat_statuscode');
                    break;
                case 'failed':
                    $query->where('satusehat_statuscode', '500');
                    break;
                default:
                    $query->where('satusehat_statuscode', '200');
            }
            return $query;
        });
        $q->when($request['tanggal_awal'] != '', function ($query) use ($request) {

            $query->whereBetween('onset_datetime', [
                Carbon::createFromFormat('Y-m-d', $request['tanggal_awal'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $request['tanggal_akhir'])->endOfDay(),
            ]);

            return $query;
        });


        return $q;
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

    public function updateCondition($request =  [], $id)
    {
        $data = $this->model->find($id);
        $data->encounter_original_code = $request['encounter_original_code'];
        $data->rank = $request['rank'];
        $data->code_icd = $request['code_icd'];
        $data->code_icd_display = $request['code_icd_display'];
        $data->onset_datetime = $request['onset_datetime'];
        $data->record_date = $request['record_date'];
        $data->satusehat_send = $request['satusehat_send'];
        $data->update();

        return $data;
    }
}

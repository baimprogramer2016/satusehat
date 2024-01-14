<?php

namespace App\Repositories\MedicationRequest;

use App\Models\MedicationRequest;
use Illuminate\Support\Facades\Log;

class MedicationRequestRepository implements MedicationRequestInterface
{
    private $model;
    public function __construct(MedicationRequest $medicationRequestModel)
    {
        $this->model = $medicationRequestModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataMedicationRequestByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }


    // public function updateDataBundleConditionJob($param = [], $rank)
    // {
    //     $data = $this->model
    //         ->where('encounter_original_code', $param['encounter_original_code'])
    //         ->where('rank', $rank)->first();
    //     if ($data->count() > 1) {
    //         $data->satusehat_id = $param['satusehat_id'];
    //         $data->satusehat_send = $param['satusehat_send'];
    //         $data->satusehat_date = $param['satusehat_date'];
    //         $data->satusehat_statuscode = $param['satusehat_statuscode'];
    //         $data->satusehat_request = $param['satusehat_request'];
    //         $data->satusehat_response = $param['satusehat_response'];
    //         $data->update();
    //     }
    //     return $data;
    // }

    public function getDataMedicationRequestByOriginalCodeReady($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }

    # update medication
    public function updateDataBundleMedicationJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id_medication')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_medication = $param['satusehat_id'];
            $data->update();
        }
        return $data;
    }

    public function updateDataBundleMedicationRequestJob($param = [])
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
}

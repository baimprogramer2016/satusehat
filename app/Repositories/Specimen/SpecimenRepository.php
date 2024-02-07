<?php

namespace App\Repositories\Specimen;

use App\Models\Specimen;
use Carbon\Carbon;

class SpecimenRepository implements SpecimenInterface
{
    private $model;
    public function __construct(
        Specimen $specimenInterface
    ) {
        $this->model = $specimenInterface;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataSpecimenByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleSpecimenJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id_specimen')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_specimen = $param['satusehat_id'];
            $data->satusehat_send_specimen = $param['satusehat_send'];
            $data->satusehat_date_specimen = $param['satusehat_date'];
            $data->satusehat_statuscode_specimen = $param['satusehat_statuscode'];
            $data->satusehat_request_specimen = $param['satusehat_request'];
            $data->satusehat_response_specimen = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }
}

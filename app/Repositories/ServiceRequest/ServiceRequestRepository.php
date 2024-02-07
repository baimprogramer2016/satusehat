<?php

namespace App\Repositories\ServiceRequest;

use App\Models\ServiceRequest;
use Carbon\Carbon;

class ServiceRequestRepository implements ServiceRequestInterface
{
    private $model;
    public function __construct(
        ServiceRequest $serviceRequestInterface
    ) {
        $this->model = $serviceRequestInterface;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataServiceRequestByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleServiceRequestJob($param = [])
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

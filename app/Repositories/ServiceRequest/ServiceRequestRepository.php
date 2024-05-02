<?php

namespace App\Repositories\ServiceRequest;

use App\Models\ServiceRequest;
use Carbon\Carbon;

class ServiceRequestRepository implements ServiceRequestInterface
{
    private $model;
    public function __construct(
        ServiceRequest $serviceRequestModel
    ) {
        $this->model = $serviceRequestModel;
    }
    public function getQuery()
    {
        return $this->model->query()->where('procedure', 'lab');
    }

    public function getDataServiceRequestByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
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

    public function getDataServiceRequestFind($id)
    {
        return $this->model->find($id);
    }
    public function updateStatusServiceRequest($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id' => $satusehat_id,
                'satusehat_request' => $request,
                'satusehat_response' => $response,
                'satusehat_send' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
}

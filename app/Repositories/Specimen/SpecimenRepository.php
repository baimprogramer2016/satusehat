<?php

namespace App\Repositories\Specimen;

use App\Models\Specimen;
use Carbon\Carbon;

class SpecimenRepository implements SpecimenInterface
{
    private $model;
    public function __construct(
        Specimen $specimenModel
    ) {
        $this->model = $specimenModel;
    }
    public function getQuery()
    {
        return $this->model->query()->where('procedure', 'lab');
    }

    public function getDataSpecimenByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleSpecimenJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->where('procedure', 'lab')
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

    public function getDataSpecimenFind($id)
    {
        return $this->model->find($id);
    }
    public function updateStatusSpecimen($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id_specimen' => $satusehat_id,
                'satusehat_request_specimen' => $request,
                'satusehat_response_specimen' => $response,
                'satusehat_send_specimen' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode_specimen' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date_specimen' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
}

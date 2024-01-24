<?php

namespace App\Repositories\MedicationDispense;

use App\Models\MedicationDispense;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicationDispenseRepository implements MedicationDispenseInterface
{
    private $model;
    public function __construct(MedicationDispense $medicationDispenseModel)
    {
        $this->model = $medicationDispenseModel;
    }


    public function getQuery()
    {
        return $this->model->query();
    }
    public function getDataMedicationDispenseFind($id)
    {
        return $this->model->find($id);
    }

    # untuk mendapatkan keseluruhan data
    public function getDataMedicationDispenseByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
    public function updateDataBundleMedicationDispenseJob($param = [])
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
    public function updateStatusMedicationDispense($id, $satusehat_id, $request, $response)
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

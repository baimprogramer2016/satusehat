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
    public function getDataMedicationDispenseBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
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

    public function getDataMedicationDispenseReadyJob()
    {
        return $this->model
            ->select('ss_medication_dispense.*')
            ->join('ss_encounter', 'ss_medication_dispense.encounter_original_code', '=', 'ss_encounter.original_code')
            ->join('ss_medication_request', 'ss_medication_dispense.encounter_original_code', '=', 'ss_medication_request.encounter_original_code')
            ->where('ss_medication_dispense.identifier_1', '=', DB::raw('ss_medication_request.identifier_1'))
            ->where('ss_medication_dispense.identifier_2', '=', DB::raw('ss_medication_request.identifier_2'))
            ->whereNotNull('ss_medication_request.satusehat_id')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->where('ss_encounter.satusehat_send', '=', '1')
            ->where('ss_encounter.satusehat_statuscode', '=', '200')
            ->where('ss_medication_dispense.satusehat_send', '!=', '1')
            ->whereNull('ss_medication_dispense.satusehat_id')
            ->whereNull('ss_medication_dispense.satusehat_statuscode')
            ->get();
    }
}

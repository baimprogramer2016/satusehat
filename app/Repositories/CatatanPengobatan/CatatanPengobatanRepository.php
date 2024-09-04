<?php

namespace App\Repositories\CatatanPengobatan;

use App\Models\CatatanPengobatan;
use App\Models\MedicationDispense;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatatanPengobatanRepository implements CatatanPengobatanInterface
{
    private $model;
    public function __construct(MedicationDispense $medicationDispenseModel)
    {
        $this->model = $medicationDispenseModel;
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


        return $q;
    }
    public function getDataCatatanPengobatanFind($id)
    {
        return $this->model->find($id);
    }

    # untuk mendapatkan keseluruhan data
    public function getDataCatatanPengobatanByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
    public function getDataCatatanPengobatanBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
    public function updateDataBundleCatatanPengobatanJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_catatan_pengobatan = $param['satusehat_id'];
            $data->satusehat_send_catatan_pengobatan = $param['satusehat_send'];
            $data->satusehat_date_catatan_pengobatan = $param['satusehat_date'];
            $data->satusehat_statuscode_catatan_pengobatan = $param['satusehat_statuscode'];
            $data->satusehat_request_catatan_pengobatan = $param['satusehat_request'];
            $data->satusehat_response_catatan_pengobatan = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }
    public function updateStatusCatatanPengobatan($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id_catatan_pengobatan' => $satusehat_id,
                'satusehat_request_catatan_pengobatan' => $request,
                'satusehat_response_catatan_pengobatan' => $response,
                'satusehat_send_catatan_pengobatan' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode_catatan_pengobatan' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date_catatan_pengobatan' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }

    public function getDataCatatanPengobatanReadyJob()
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
            ->where('ss_medication_dispense.satusehat_send_catatan_pengobatan', '!=', '1')
            ->whereNull('ss_medication_dispense.satusehat_id_catatan_pengobatan')
            ->whereNull('ss_medication_dispense.satusehat_statuscode_catatan_pengobatan')
            ->whereNotNull('ss_medication_dispense.satusehat_id')
            ->get();
    }
}

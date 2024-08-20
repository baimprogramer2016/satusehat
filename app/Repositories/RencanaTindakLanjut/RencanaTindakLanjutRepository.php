<?php

namespace App\Repositories\RencanaTindakLanjut;

use App\Models\RencanaTindakLanjut;
use Carbon\Carbon;

class RencanaTindakLanjutRepository implements RencanaTindakLanjutInterface
{
    private $model;
    public function __construct(RencanaTindakLanjut $rencanaTindakLanjutInterface)
    {
        $this->model = $rencanaTindakLanjutInterface;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataRencanaTindakLanjutById($id)
    {
        return $this->model->where('id', $id)->get();
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

            $query->whereBetween('created', [
                Carbon::createFromFormat('Y-m-d', $request['tanggal_awal'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $request['tanggal_akhir'])->endOfDay(),
            ]);

            return $query;
        });


        return $q;
    }

    public function getDataRencanaTindakLanjutByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }
    public function getDataRencanaTindakLanjutBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }

    public function updateDataBundleRencanaTindakLanjutJob($param = [])
    {
        $data = $this->model->where('encounter_original_code', $param['encounter_original_code'])
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
    public function getDataRencanaTindakLanjutFind($id)
    {
        return $this->model->find($id);
    }

    public function updateStatusRencanaTindakLanjut($id, $satusehat_id, $request, $response)
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

    public function getDataRencanaTindakLanjutReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_rencana_tindak_lanjut.encounter_original_code', 'ss_encounter.original_code')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')
            ->where('ss_rencana_tindak_lanjut.satusehat_send', '!=', 1)
            ->whereNull('ss_rencana_tindak_lanjut.satusehat_statuscode')
            // ->whereIn('ss_encounter.original_code', ['A012300118'])
            ->select('ss_rencana_tindak_lanjut.*')
            ->get();
    }
}

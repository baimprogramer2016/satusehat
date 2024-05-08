<?php

namespace App\Repositories\Composition;

use App\Models\Composition;
use Carbon\Carbon;

class CompositionRepository implements CompositionInterface
{
    private $model;
    public function __construct(Composition $compositionInterface)
    {
        $this->model = $compositionInterface;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataCompositionById($id)
    {
        return $this->model->where('id', $id)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataCompositionByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }
    public function getDataCompositionBundleByOriginalCode($original_code)
    {
        return $this->model->whereNull('satusehat_id')->where('encounter_original_code', $original_code)->get();
    }

    public function updateDataBundleCompositionJob($param = [])
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
    public function getDataCompositionFind($id)
    {
        return $this->model->find($id);
    }

    public function updateStatusComposition($id, $satusehat_id, $request, $response)
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

    public function getDataCompositionReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_composition.encounter_original_code', 'ss_encounter.original_code')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')
            ->where('ss_composition.satusehat_send', '!=', 1)
            ->whereNull('ss_composition.satusehat_statuscode')
            // ->whereIn('original_code', ['A112306380'])
            ->select('ss_composition.*')
            ->get();
    }
}

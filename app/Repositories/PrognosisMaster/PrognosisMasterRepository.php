<?php

namespace App\Repositories\PrognosisMaster;

use App\Models\PrognosisCode;
use App\Models\PrognosisMaster;
use Carbon\Carbon;
use Throwable;

class PrognosisMasterRepository implements PrognosisMasterInterface
{
    private $model;

    private $model_code;

    public function __construct(PrognosisMaster $prognosis_master, PrognosisCode $prognosis_code)
    {
        $this->model = $prognosis_master;
        $this->model_code = $prognosis_code;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getPrognosisMasterId($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updatePrognosisMasterCode($param)
    {
        try {
            //ambil dulu info kodenya
            $data_kode = $this->model_code->where('code', $param['kode_prognosis'])->first();

            $data = $this->model->find($param['id_prognosis_master']);
            $data->code_satusehat = $param['kode_prognosis'];
            $data->code_satusehat_display = $data_kode->display;
            $data->code_system = $data_kode->code_system;
            $data->update();
            return $data;
        } catch (Throwable $e) {
            return $e;
        }
    }
}

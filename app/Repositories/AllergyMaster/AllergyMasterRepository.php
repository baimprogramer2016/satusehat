<?php

namespace App\Repositories\AllergyMaster;

use App\Models\AllergyCode;
use App\Models\AllergyMaster;
use Carbon\Carbon;
use Throwable;

class AllergyMasterRepository implements AllergyMasterInterface
{
    private $model;

    private $model_code;

    public function __construct(AllergyMaster $allergy_master, AllergyCode $allergy_code)
    {
        $this->model = $allergy_master;
        $this->model_code = $allergy_code;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getAllergyMasterId($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updateAllergyMasterCode($param)
    {
        try {
            //ambil dulu info kodenya
            $data_kode = $this->model_code->where('code', $param['kode_allergy'])->first();

            $data = $this->model->find($param['id_allergy_master']);
            $data->code_satusehat = $param['kode_allergy'];
            $data->code_satusehat_display = $data_kode->display;
            $data->code_system = $data_kode->code_system;
            $data->category = $data_kode->category;
            $data->update();
            return $data;
        } catch (Throwable $e) {
            return $e;
        }
    }
}

<?php

namespace App\Repositories\RencanaTindakLanjutMaster;

use App\Models\RencanaTindakLanjutCode;
use App\Models\RencanaTindakLanjutMaster;
use Carbon\Carbon;
use Throwable;

class RencanaTindakLanjutMasterRepository implements RencanaTindakLanjutMasterInterface
{
    private $model;

    private $model_code;

    public function __construct(RencanaTindakLanjutMaster $rencana_tindak_lanjut_master, RencanaTindakLanjutCode $rencana_tindak_lanjut_code)
    {
        $this->model = $rencana_tindak_lanjut_master;
        $this->model_code = $rencana_tindak_lanjut_code;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getRencanaTindakLanjutMasterId($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updateRencanaTindakLanjutMasterCode($param)
    {
        try {
            //ambil dulu info kodenya
            $data_kode = $this->model_code->where('code', $param['kode_rencana_tindak_lanjut'])->first();

            $data = $this->model->find($param['id_rencana_tindak_lanjut_master']);
            $data->code_satusehat = $param['kode_rencana_tindak_lanjut'];
            $data->code_satusehat_display = $data_kode->display;
            $data->code_system = $data_kode->code_system;
            $data->update();
            return $data;
        } catch (Throwable $e) {
            return $e;
        }
    }
}

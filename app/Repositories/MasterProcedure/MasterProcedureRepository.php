<?php

namespace App\Repositories\MasterProcedure;

use App\Models\MasterProcedure;
use Carbon\Carbon;

class MasterProcedureRepository implements MasterProcedureInterface
{
    private $model;

    public function __construct(MasterProcedure $master_procedure)
    {
        $this->model = $master_procedure;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getMasterProcedureId($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updateMasterProcedureSnomed($param)
    {
        $data = $this->model->find($param['id_master_procedure']);
        $data->snomed_code = $param['snomed_code'];
        $data->update();
        return $data;
    }

    public function updateMasterProcedureLoinc($param)
    {
        $data = $this->model->find($param['id_master_procedure']);
        $data->loinc_code = $param['loinc_code'];
        $data->update();
        return $data;
    }
    public function updateMasterProcedureCategory($param)
    {
        $data = $this->model->find($param['id_master_procedure']);
        $data->category_request = $param['category_request'];
        $data->update();
        return $data;
    }
}

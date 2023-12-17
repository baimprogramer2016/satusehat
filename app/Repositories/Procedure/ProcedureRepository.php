<?php

namespace App\Repositories\Procedure;

use App\Models\Procedure;

class ProcedureRepository implements ProcedureInterface
{
    private $model;
    public function __construct(Procedure $ProcedureModel)
    {
        $this->model = $ProcedureModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataProcedureByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }
}

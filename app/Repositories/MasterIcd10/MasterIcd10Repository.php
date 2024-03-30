<?php

namespace App\Repositories\MasterIcd10;

use App\Models\MasterIcd10;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Traits\GeneralTrait;

class MasterIcd10Repository implements MasterIcd10Interface
{

    use GeneralTrait;
    private $model;
    public function __construct(MasterIcd10 $masterIcd10Model)
    {
        $this->model = $masterIcd10Model;
    }

    public function getQuery($param)
    {
        // return $this->model->where('code2', 'like', '%' . $param . '%')
        //     ->where('description', 'like', '%' . $param . '%')->get();
        return $this->model->where('code2', 'ilike', '%' . $param . '%')
            ->orwhere('description', 'ilike', '%' . $param . '%')->get();
    }
}

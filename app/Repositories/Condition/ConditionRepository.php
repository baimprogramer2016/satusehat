<?php

namespace App\Repositories\Condition;

use App\Models\Condition;

class ConditionRepository implements ConditionInterface
{
    private $model;
    public function __construct(Condition $conditionModel)
    {
        $this->model = $conditionModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataConditionByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->get();
    }

    public function getQuery()
    {
        return $this->model->query();
    }
}

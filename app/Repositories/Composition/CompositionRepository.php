<?php

namespace App\Repositories\Composition;

use App\Models\Composition;

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
}

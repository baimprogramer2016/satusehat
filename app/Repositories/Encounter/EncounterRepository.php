<?php

namespace App\Repositories\Encounter;

use App\Models\Encounter;

class EncounterRepository implements EncounterInterface
{
    private $model;
    public function __construct(Encounter $encounterModel)
    {
        $this->model = $encounterModel;
    }

    # untuk mendapatkan keseluruhan data
    public function getQuery()
    {
        return $this->model->query();
    }
}

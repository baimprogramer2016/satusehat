<?php

namespace App\Repositories\Medication;

use App\Models\Medication;
use Carbon\Carbon;

class MedicationRepository implements MedicationInterface
{
    private $model;

    public function __construct(Medication $medication)
    {
        $this->model = $medication;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getMedicationId($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updateMedicationKfa($param)
    {
        $data = $this->model->find($param['id_medication']);
        $data->kfa_code = $param['kode_kfa'];
        $data->update();
        return $data;
    }
}

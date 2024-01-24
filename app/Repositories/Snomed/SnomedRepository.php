<?php

namespace App\Repositories\Snomed;

use App\Models\Snomed;
use Carbon\Carbon;

class SnomedRepository implements SnomedInterface
{
    private $model;
    public function __construct()
    {
        $this->model = new Snomed();
    }

    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }
    # untuk mendapatkan keseluruhan data
    public function getDataSnomed()
    {
        return $this->model->orderBy('id', 'ASC')->get();
    }

    public function storeSnomed($request =  [])
    {

        $this->model->create([
            "snomed_code" => $request['snomed_code'],
            "snomed_display"  => $request['snomed_display'],
        ]);

        return $this->model;
    }

    public function getDataSnomedFind($id)
    {
        return $this->model->find($id);
    }
    public function updateSnomed($request = [], $id)
    {
        $data = $this->getDataSnomedFind($id);
        $data->snomed_code = $request['snomed_code'];
        $data->snomed_display = $request['snomed_display'];
        $data->update();

        return $data;
    }

    public function deleteSnomed($id)
    {
        # identifikasi data yang ingin di delete
        $delete = $this->model->find($id);
        # delete
        $delete->delete();

        return $delete;
    }
}

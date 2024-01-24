<?php

namespace App\Repositories\Loinc;

use App\Models\Loinc;
use Carbon\Carbon;

class LoincRepository implements LoincInterface
{
    private $model;
    public function __construct()
    {
        $this->model = new Loinc();
    }

    # untuk mendapatkan keseluruhan data
    public function getDataLoinc()
    {
        return $this->model->orderBy('id', 'ASC')->get();
    }

    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }
    public function storeLoinc($request =  [])
    {

        $this->model->create([
            "loinc_code" => $request['loinc_code'],
            "loinc_display"  => $request['loinc_display'],
        ]);

        return $this->model;
    }

    public function getDataLoincFind($id)
    {
        return $this->model->find($id);
    }
    public function updateLoinc($request = [], $id)
    {
        $data = $this->getDataLoincFind($id);
        $data->loinc_code = $request['loinc_code'];
        $data->loinc_display = $request['loinc_display'];
        $data->update();

        return $data;
    }

    public function deleteLoinc($id)
    {
        # identifikasi data yang ingin di delete
        $delete = $this->model->find($id);
        # delete
        $delete->delete();

        return $delete;
    }
}

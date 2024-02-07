<?php

namespace App\Repositories\Snomed;

use App\Models\DescResource;
use App\Models\Snomed;
use Carbon\Carbon;

class SnomedRepository implements SnomedInterface
{
    private $model;
    private $desc_resource_model;
    public function __construct()
    {
        $this->model = new Snomed();
        $this->desc_resource_model = new DescResource();
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
            "description"  => $request['description'],
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
        $data->description = $request['description'];
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

    public function getDescResource()
    {
        return $this->desc_resource_model->get();
    }
}

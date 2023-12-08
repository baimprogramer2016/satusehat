<?php

namespace App\Repositories\Practitioner;

use App\Models\Practitioner;

class PractitionerRepository implements PractitionerInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Practitioner();
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataPractitionerFind($id)
    {
        return $this->model->find($id);
    }

    public function updatePractitioner($request = [], $id)
    {
        $data = $this->getDataPractitionerFind($id);

        $data->nik = $request['nik'];
        $data->update();

        return $data;
    }
    public function updateIhsPractitioner($request = [], $id)
    {
        $data = $this->getDataPractitionerFind($id);

        $data->nik = $request['nik'];
        $data->satusehat_id = $request['satusehat_id'];
        $data->satusehat_process = 1;
        $data->update();

        return $data;
    }
}

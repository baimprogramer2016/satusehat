<?php

namespace App\Repositories\Patient;

use App\Models\Patient;

class PatientRepository implements PatientInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Patient();
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataPatientFind($id)
    {
        return $this->model->find($id);
    }

    public function updatePatient($request = [], $id)
    {
        $data = $this->getDataPatientFind($id);

        $data->nik = $request['nik'];
        $data->update();

        return $data;
    }
    public function updateIhsPatient($request = [], $id)
    {
        $data = $this->getDataPatientFind($id);

        $data->nik = $request['nik'];
        $data->satusehat_id = $request['satusehat_id'];
        $data->satusehat_process = 1;
        $data->update();

        return $data;
    }
}

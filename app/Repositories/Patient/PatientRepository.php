<?php

namespace App\Repositories\Patient;

use App\Models\Patient;
use Carbon\Carbon;

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
    public function updateIhsPatient($request = [])
    {
        $data = $this->getDataPatientFind($request['id']);

        $data->satusehat_id = $request['satusehat_id'];
        $data->satusehat_process = $request['satusehat_process'];
        $data->satusehat_statuscode = $request['satusehat_statuscode'];
        $data->satusehat_message = $request['satusehat_message'];
        $data->satusehat_date = Carbon::now()->format('Y-m-d H:i:s');
        $data->update();

        return $data;
    }

    public function getDataPatientReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->whereNull('satusehat_id')
            ->where('satusehat_process', '!=', 1)
            ->whereNull('satusehat_statuscode')
            ->get();
    }
}

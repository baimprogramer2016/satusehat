<?php

namespace App\Repositories\Practitioner;

use App\Models\Practitioner;
use Carbon\Carbon;

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
    public function updateIhsPractitioner($request = [])
    {
        $data = $this->getDataPractitionerFind($request['id']);

        $data->satusehat_id = $request['satusehat_id'];
        $data->satusehat_process = $request['satusehat_process'];
        $data->satusehat_statuscode = $request['satusehat_statuscode'];
        $data->satusehat_message = $request['satusehat_message'];
        $data->satusehat_date = Carbon::now()->format('Y-m-d H:i:s');
        $data->update();

        return $data;
    }

    public function getDataPractitionerReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD'))
            ->whereNull('satusehat_id')
            ->where('satusehat_process', '!=', 1)
            ->whereNull('satusehat_statuscode')
            ->get();
    }
}

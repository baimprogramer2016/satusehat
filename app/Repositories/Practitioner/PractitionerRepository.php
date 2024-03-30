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
        $data->satusehat_statuscode = null;
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

    # data untuk Job/ Scheduler
    public function getDataPractitionerReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD'))
            ->whereNull('satusehat_id')
            ->where('satusehat_process', '!=', 1)
            ->whereNull('satusehat_statuscode')
            ->get();
    }


    public function storePractitioner($request = [])
    {

        if ($request['satusehat_id'] == config('constan.error_message.id_ihs_error') || empty($request['satusehat_id'])) {
            $satusehat_id = null;
            $satusehat_send = 4;
        } else {
            $satusehat_id = $request['satusehat_id'];
            $satusehat_send = 1;
        }
        $this->model->create([
            'nik' => $request['nik'],
            'name' => $request['name'],
            'satusehat_id' => $satusehat_id,
            'satusehat_process' => $satusehat_send,
            'original_code' => $request['original_code'],
            // Tambahkan field-field lainnya di sini...
        ]);

        return $this->model;
    }
    public function getDataPractitionerOriginalCode($id)
    {
        return $this->model->where('original_code', $id)->whereNotNull('satusehat_id')->first();
    }
}

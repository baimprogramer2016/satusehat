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
    public function getDataPatientOriginalCode($id)
    {
        return $this->model->where('original_code', $id)->whereNotNull('satusehat_id')->first();
    }

    public function updatePatient($request = [], $id)
    {
        $data = $this->getDataPatientFind($id);
        $data->nik = $request['nik'];
        $data->satusehat_statuscode = null;
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

    # data untuk Job/ Scheduler
    public function getDataPatientReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->whereNull('satusehat_id')
            ->where('satusehat_process', '!=', 1)
            ->whereNull('satusehat_statuscode')
            // ->whereIn('nik', ['1277032308980006', '1203217010990001'])
            ->get();
    }
    public function storePatient($request = [])
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
}

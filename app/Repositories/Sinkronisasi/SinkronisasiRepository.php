<?php

namespace App\Repositories\Sinkronisasi;

use App\Models\Sinkronisasi;

class SinkronisasiRepository implements SinkronisasiInterface
{
    private $model;
    public function __construct(Sinkronisasi $sinkronisasiModel)
    {
        $this->model = $sinkronisasiModel;
    }

    # untuk mendapatkan keseluruhan dataS

    public function getDataSinkronisasi()
    {
        return $this->model->get();
    }

    public function insertSinkronisasi($request = [])
    {

        if (array_key_exists('status', $request)) {
            $sts = 1;
        } else {
            $sts = 0;
        }
        $input = [
            "kode" => $request['kode'],
            "description" => $request['description'],
            "query" => $request['query'],
            "odbc" => $request['odbc'],
            "status" => $sts,
            "command" => $request['command'],
            "target" => $request['target'],
            "record" => $request['record'],
            "part" => $request['part'],
            "page" => $request['page'],
            "upload" => $request['upload'],
            "prefix" => $request['prefix'],
            "cron" => $request['cron'],
            "sp" => $request['sp'],
        ];

        $this->model->create($input);
        return  $this->model;
    }
    public function getDataSinkronisasiById($id)
    {
        return $this->model->find($id);
    }


    public function updateDataSinkronisasi($request = [], $id)
    {
        if (array_key_exists('status', $request)) {
            $sts = 1;
        } else {
            $sts = 0;
        }
        $data = $this->getDataSinkronisasiById($id);

        $data->kode = $request['kode'];
        $data->query = $request['query'];
        $data->description = $request['description'];
        $data->odbc = $request['odbc'];
        $data->status = $sts;
        $data->command = $request['command'];
        $data->target = $request['target'];
        $data->prefix = $request['prefix'];
        $data->cron = $request['cron'];
        $data->sp = $request['sp'];
        $data->update();

        return $data;
    }
    public function deleteDataDinkronisasi($id)
    {
        $data = $this->model->find($id);
        $data->delete();
        return $data;
    }

    public function getDataSinkronisasiReadyJob()
    {
        return $this->model->where('status', 1)->get();
    }
}

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
        $input = [
            "kode" => $request['kode'],
            "description" => $request['description'],
            "query" => $request['query'],
            "odbc" => $request['odbc'],
            "target" => $request['target'],
            "record" => $request['record'],
            "part" => $request['part'],
            "page" => $request['page'],
            "upload" => $request['upload'],
            "active" => 1,
            "target" => $request['target'],
            "prefix" => $request['prefix'],
            "schedule" => $request['schedule'],
            "sp" => $request['sp'],
        ];

        $this->model->create($input);
        return  $this->model;
    }
}

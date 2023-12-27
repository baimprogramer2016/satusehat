<?php

namespace App\Repositories\Sinkronisasi;

interface SinkronisasiInterface
{
    public function getDataSinkronisasi();

    public function insertSinkronisasi($request = []);

    public function getDataSinkronisasiById($id);
    public function updateDataSinkronisasi($request = [], $id);

    public function deleteDataDinkronisasi($id);

    public function getDataSinkronisasiReadyJob();
}

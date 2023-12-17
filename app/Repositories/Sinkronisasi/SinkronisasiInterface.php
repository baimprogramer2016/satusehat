<?php

namespace App\Repositories\Sinkronisasi;

interface SinkronisasiInterface
{
    public function getDataSinkronisasi();

    public function insertSinkronisasi($request = []);
}

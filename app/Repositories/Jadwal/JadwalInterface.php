<?php

namespace App\Repositories\Jadwal;

interface JadwalInterface
{
    public function getDataJadwal();

    public function getDataJadwalById($id);

    public function updateJadwal($cron, $status, $id);
}

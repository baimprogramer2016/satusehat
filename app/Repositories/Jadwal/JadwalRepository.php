<?php

namespace App\Repositories\Jadwal;

use App\Models\Jadwal;

class JadwalRepository implements JadwalInterface
{
    private $model;
    public function __construct(Jadwal $jadwalModel)
    {
        $this->model = $jadwalModel;
    }

    # untuk mendapatkan keseluruhan dataS

    public function getDataJadwal()
    {
        return $this->model->get();
    }

    public function getDataJadwalById($id)
    {
        return $this->model->find($id);
    }

    public function updateJadwal($cron, $status, $id)
    {
        $data = $this->getDataJadwalById($id);
        $data->cron = $cron;
        $data->status = ($status == 'on') ? 1 : 0;
        $data->update();
        return $data;
    }
}

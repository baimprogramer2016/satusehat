<?php

namespace App\Repositories\Jadwal;

use App\Models\BundleSet;
use App\Models\Jadwal;

class JadwalRepository implements JadwalInterface
{
    private $model;
    private $model_bundle_set;
    public function __construct(Jadwal $jadwalModel, BundleSet $bundleSet)
    {
        $this->model = $jadwalModel;
        $this->model_bundle_set = $bundleSet;
    }

    # untuk mendapatkan keseluruhan dataS

    public function getDataJadwal()
    {
        return $this->model->get();
    }
    public function getDataBundleSetQuery()
    {
        return $this->model_bundle_set->query();
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
    public function getDataBundleSet()
    {
        return $this->model_bundle_set->orderBy('resource', 'asc')->get();
    }

    public function updateAturBundle($resource, $isChecked)
    {

        $data = $this->model_bundle_set->where('resource', $resource)->update([
            "status" => $isChecked
        ]);

        return $data;
    }
}

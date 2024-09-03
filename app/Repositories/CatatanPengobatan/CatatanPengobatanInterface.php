<?php

namespace App\Repositories\CatatanPengobatan;

interface CatatanPengobatanInterface
{
    public function getQuery($request = []);
    public function getDataCatatanPengobatanByOriginalCode($original_code);
    public function getDataCatatanPengobatanBundleByOriginalCode($original_code);

    public function updateDataBundleCatatanPengobatanJob($param = []);
    public function getDataCatatanPengobatanFind($id);
    public function updateStatusCatatanPengobatan($id, $satusehat_id, $request, $response);

    public function getDataCatatanPengobatanReadyJob();
}

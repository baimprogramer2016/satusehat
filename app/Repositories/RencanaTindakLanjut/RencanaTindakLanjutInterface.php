<?php

namespace App\Repositories\RencanaTindakLanjut;

interface RencanaTindakLanjutInterface
{
    public function getQuery($request = []);
    public function getDataRencanaTindakLanjutById($id);
    public function getDataRencanaTindakLanjutByOriginalCode($original_code);
    public function getDataRencanaTindakLanjutBundleByOriginalCode($original_code);
    public function updateDataBundleRencanaTindakLanjutJob($param = []);

    public function getDataRencanaTindakLanjutFind($id);
    public function updateStatusRencanaTindakLanjut($id, $satusehat_id, $request, $response);

    public function getDataRencanaTindakLanjutReadyJob();
}

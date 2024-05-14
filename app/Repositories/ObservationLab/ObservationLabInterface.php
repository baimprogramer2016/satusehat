<?php

namespace App\Repositories\ObservationLab;

interface ObservationLabInterface
{
    public function getQuery();
    public function getObservationLabQuery();

    public function getDataObservationLabByOriginalCode($original_code);
    public function getDataObservationLabBundleByOriginalCode($original_code);

    public function getDataObservationLabFind($uuid);
    public function updateStatusObservationLab($id, $satusehat_id, $request, $response);
    public function getDataObservationLabReadyJob();
}

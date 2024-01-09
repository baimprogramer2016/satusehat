<?php

namespace App\Repositories\Observation;

interface ObservationInterface
{
    public function getQuery();
    public function getDataObservationByOriginalCode($original_code);

    public function updateDataBundleObservationJob($param = []);

    public function getDataObservationFind($id);
    public function updateStatusObservation($id, $satusehat_id, $request, $response);
}

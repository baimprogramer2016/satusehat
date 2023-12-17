<?php

namespace App\Repositories\Observation;

interface ObservationInterface
{
    public function getQuery();
    public function getDataObservationByOriginalCode($original_code);
}

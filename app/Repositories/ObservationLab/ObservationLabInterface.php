<?php

namespace App\Repositories\ObservationLab;

interface ObservationLabInterface
{
    public function getQuery();
    public function getObservationLabQuery();

    public function getDataObservationLabByOriginalCode($original_code);
}

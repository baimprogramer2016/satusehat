<?php

namespace App\Repositories\Specimen;

interface SpecimenInterface
{
    public function getQuery();

    public function getDataSpecimenByOriginalCode($original_code);
    public function updateDataBundleSpecimenJob($param = []);
}

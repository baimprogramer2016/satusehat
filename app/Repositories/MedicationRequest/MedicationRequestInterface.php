<?php

namespace App\Repositories\MedicationRequest;

interface MedicationRequestInterface
{
    public function getQuery();
    public function getDataMedicationRequestByOriginalCode($original_code);

    // public function updateDataBundleMedicationRequestJob($param = [], $rank);
}

<?php

namespace App\Repositories\MedicationRequest;

interface MedicationRequestInterface
{
    public function getQuery();
    public function getDataMedicationRequestByOriginalCode($original_code);

    // public function updateDataBundleMedicationRequestJob($param = [], $rank);

    public function getDataMedicationRequestByOriginalCodeReady($original_code);

    public function updateDataBundleMedicationJob($param = []);
    public function updateDataBundleMedicationRequestJob($param = []);
}

<?php

namespace App\Repositories\MedicationDispense;

interface MedicationDispenseInterface
{
    public function getQuery();
    public function getDataMedicationDispenseByOriginalCode($original_code);

    public function updateDataBundleMedicationDispenseJob($param = []);
}

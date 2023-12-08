<?php

namespace App\Repositories\Medication;


interface MedicationInterface
{
    public function getQuery();
    public function getMedicationId($id);
}

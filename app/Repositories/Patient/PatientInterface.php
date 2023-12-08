<?php

namespace App\Repositories\Patient;


interface PatientInterface
{
    public function getQuery();
    public function getDataPatientFind($id);
    public function updatePatient($request = [], $id);
    public function updateIhsPatient($request = [], $id);
}

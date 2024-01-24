<?php

namespace App\Repositories\Loinc;

interface LoincInterface
{
    public function getQuery();
    public function getDataLoinc();
    public function storeLoinc($request =  []);
    public function getDataLoincFind($id);
    public function updateLoinc($request = [], $id);
    public function deleteLoinc($id);
}

<?php

namespace App\Repositories\AllergyMaster;


interface AllergyMasterInterface
{
    public function getQuery();
    public function getAllergyMasterId($id);

    public function updateAllergyMasterCode($param);
}

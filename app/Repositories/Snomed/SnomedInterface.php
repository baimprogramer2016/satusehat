<?php

namespace App\Repositories\Snomed;

interface SnomedInterface
{
    public function getQuery();
    public function getDataSnomed();
    public function storeSnomed($request =  []);
    public function getDataSnomedFind($id);
    public function updateSnomed($request = [], $id);
    public function deleteSnomed($id);
}

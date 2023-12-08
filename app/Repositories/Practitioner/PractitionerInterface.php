<?php

namespace App\Repositories\Practitioner;


interface PractitionerInterface
{
    public function getQuery();
    public function getDataPractitionerFind($id);
    public function updatePractitioner($request = [], $id);
    public function updateIhsPractitioner($request = [], $id);
}

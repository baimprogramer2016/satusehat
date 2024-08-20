<?php

namespace App\Repositories\Encounter;

interface EncounterInterface
{
    public function getQuery($request = []);

    public function getDataBundleReadyJob();


    public function updateDataBundleEncounterJob($param = []);

    public function getDataEncounterFind($id);
    public function updateStatusEncounter($id, $satusehat_id, $request, $response);

    public function getDataEncounterByOriginalCode($original_code);

    public function storeEncounter($request =  []);
    public function updateEncounter($request =  [], $id);
}

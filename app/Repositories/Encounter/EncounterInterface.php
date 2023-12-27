<?php

namespace App\Repositories\Encounter;

interface EncounterInterface
{
    public function getQuery();

    public function getDataBundleReadyJob();

    public function updateDataBundleEncounterJob($param = []);
}

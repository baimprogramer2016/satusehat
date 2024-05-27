<?php

namespace App\Repositories\PrognosisMaster;


interface PrognosisMasterInterface
{
    public function getQuery();
    public function getPrognosisMasterId($id);

    public function updatePrognosisMasterCode($param);
}

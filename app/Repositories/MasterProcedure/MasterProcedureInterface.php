<?php

namespace App\Repositories\MasterProcedure;


interface MasterProcedureInterface
{
    public function getQuery();
    public function getMasterProcedureId($id);

    public function updateMasterProcedureSnomed($param);
    public function updateMasterProcedureLoinc($param);
    public function updateMasterProcedureCategory($param);
}

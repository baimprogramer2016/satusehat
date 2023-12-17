<?php

namespace App\Repositories\Procedure;

interface ProcedureInterface
{
    public function getQuery();
    public function getDataProcedureByOriginalCode($original_code);
}

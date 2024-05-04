<?php

namespace App\Repositories\Procedure;

interface ProcedureInterface
{
    public function getQuery();
    public function getDataProcedureByOriginalCode($original_code);
    public function updateDataBundleProcedureJob($param = []);

    public function getDataProcedureFind($id);

    public function updateStatusProcedure($id, $satusehat_id, $request, $response);
    public function getDataProcedureReadyJob();
    public function getDataProcedureDistinct();
}

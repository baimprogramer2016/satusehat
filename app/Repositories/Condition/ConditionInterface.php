<?php

namespace App\Repositories\Condition;

interface ConditionInterface
{
    public function getQuery($request = []);
    public function getDataConditionByOriginalCode($original_code);

    public function updateDataBundleConditionJob($param = [], $rank);
    public function getDataConditionFind($id);
    public function updateStatusCondition($id, $satusehat_id, $request, $response);
    public function storeCondition($request =  []);
    public function updateCondition($request =  [], $id);
    public function getDataConditionReadyJob();
}

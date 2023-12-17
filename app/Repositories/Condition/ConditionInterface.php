<?php

namespace App\Repositories\Condition;

interface ConditionInterface
{
    public function getQuery();
    public function getDataConditionByOriginalCode($original_code);
}

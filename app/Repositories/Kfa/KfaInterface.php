<?php

namespace App\Repositories\Kfa;

interface KfaInterface
{
    public function getQuery();
    public function insertKfa($request = []);
}

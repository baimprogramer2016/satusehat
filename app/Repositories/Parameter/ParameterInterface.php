<?php

namespace App\Repositories\Parameter;



interface ParameterInterface
{
    public function getDataParameterFirst();
    public function updateParamter($request = []);
}

<?php

namespace App\Repositories\ServiceRequest;

interface ServiceRequestInterface
{
    public function getQuery();

    public function getDataServiceRequestByOriginalCode($original_code);
    public function updateDataBundleServiceRequestJob($param = []);
}

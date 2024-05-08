<?php

namespace App\Repositories\ServiceRequest;

interface ServiceRequestInterface
{
    public function getQuery();

    public function getDataServiceRequestByOriginalCode($original_code);
    public function getDataServiceRequestBundleByOriginalCode($original_code);
    public function updateDataBundleServiceRequestJob($param = []);

    public function getDataServiceRequestFind($id);

    public function updateStatusServiceRequest($id, $satusehat_id, $request, $response);

    public function getDataServiceRequestReadyJob();
}

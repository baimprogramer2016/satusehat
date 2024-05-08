<?php

namespace App\Repositories\Specimen;

interface SpecimenInterface
{
    public function getQuery();

    public function getDataSpecimenByOriginalCode($original_code);
    public function getDataSpecimenBundleByOriginalCode($original_code);
    public function updateDataBundleSpecimenJob($param = []);

    public function getDataSpecimenFind($id);

    public function updateStatusSpecimen($id, $satusehat_id, $request, $response);
    public function getDataSpecimenReadyJob();
}

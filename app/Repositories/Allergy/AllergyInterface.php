<?php

namespace App\Repositories\Allergy;

interface AllergyInterface
{
    public function getQuery();
    public function getDataAllergyById($id);
    public function getDataAllergyByOriginalCode($original_code);
    public function getDataAllergyBundleByOriginalCode($original_code);
    public function updateDataBundleAllergyJob($param = []);

    public function getDataAllergyFind($id);
    public function updateStatusAllergy($id, $satusehat_id, $request, $response);

    public function getDataAllergyReadyJob();
}

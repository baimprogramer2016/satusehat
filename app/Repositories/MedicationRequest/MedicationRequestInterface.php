<?php

namespace App\Repositories\MedicationRequest;

interface MedicationRequestInterface
{
    public function getQuery();
    public function getDataMedicationRequestByOriginalCode($original_code);

    // public function updateDataBundleMedicationRequestJob($param = [], $rank);

    public function getDataMedicationRequestByOriginalCodeReady($original_code);

    public function updateDataBundleMedicationJob($param = []);
    public function updateDataBundleMedicationRequestJob($param = []);
    public function getDataMedicationRequestFind($id);
    public function updateStatusMedicationRequest($id, $satusehat_id, $request, $response);
    public function updateStatusMedication($id, $satusehat_id, $request, $response);
    public function getDataMedicationRequestIdentifier($identifier_1, $identifier_2);
    public function getDataMedicationRequestReadyJob();
}

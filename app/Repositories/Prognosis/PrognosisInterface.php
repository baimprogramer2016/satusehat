<?php

namespace App\Repositories\Prognosis;

interface PrognosisInterface
{
    public function getQuery($request = []);
    public function getDataPrognosisById($id);
    public function getDataPrognosisByOriginalCode($original_code);
    public function getDataPrognosisBundleByOriginalCode($original_code);
    public function updateDataBundlePrognosisJob($param = []);

    public function getDataPrognosisFind($id);
    public function updateStatusPrognosis($id, $satusehat_id, $request, $response);

    public function getDataPrognosisReadyJob();
}

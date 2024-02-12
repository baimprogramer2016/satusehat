<?php

namespace App\Repositories\DiagnosticReport;

interface DiagnosticReportInterface
{
    public function getQuery();

    public function getDataDiagnosticReportByOriginalCode($original_code);
    public function updateDataBundleDiagnosticReportJob($param = []);

    public function getDataDiagnosticReportFind($id);
    public function updateStatusDiagnosticReport($id, $satusehat_id, $request, $response);
}

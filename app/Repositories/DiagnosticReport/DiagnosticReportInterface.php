<?php

namespace App\Repositories\DiagnosticReport;

interface DiagnosticReportInterface
{
    public function getQuery($request = []);
    public function getDataDiagnosticReportByOriginalCode($original_code);
    public function getDataDiagnosticReportBundleByOriginalCode($original_code);
    public function updateDataBundleDiagnosticReportJob($param = []);

    public function getDataDiagnosticReportFind($id);
    public function updateStatusDiagnosticReport($id, $satusehat_id, $request, $response);

    public function getDataDiagnosticReportReadyJob();
}

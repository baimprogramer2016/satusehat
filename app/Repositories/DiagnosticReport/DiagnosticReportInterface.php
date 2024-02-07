<?php

namespace App\Repositories\DiagnosticReport;

interface DiagnosticReportInterface
{
    public function getQuery();

    public function getDataDiagnosticReportByOriginalCode($original_code);
    public function updateDataBundleDiagnosticReportJob($param = []);
}

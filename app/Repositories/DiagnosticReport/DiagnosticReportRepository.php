<?php

namespace App\Repositories\DiagnosticReport;

use App\Models\DiagnosticReport;
use Carbon\Carbon;

class DiagnosticReportRepository implements DiagnosticReportInterface
{
    private $model;
    public function __construct(
        DiagnosticReport $diagnosticReportInterface
    ) {
        $this->model = $diagnosticReportInterface;
    }
    public function getQuery()
    {
        return $this->model->query();
    }

    public function getDataDiagnosticReportByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleDiagnosticReportJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->whereNull('satusehat_id_diagnostic_report')
            ->orderBy('id', 'asc')
            ->first();
        if (!empty($data)) {
            $data->satusehat_id_diagnostic_report = $param['satusehat_id'];
            $data->satusehat_send_diagnostic_report = $param['satusehat_send'];
            $data->satusehat_date_diagnostic_report = $param['satusehat_date'];
            $data->satusehat_statuscode_diagnostic_report = $param['satusehat_statuscode'];
            $data->satusehat_request_diagnostic_report = $param['satusehat_request'];
            $data->satusehat_response_diagnostic_report = $param['satusehat_response'];
            $data->update();
        }
        return $data;
    }
}

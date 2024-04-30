<?php

namespace App\Repositories\DiagnosticReport;

use App\Models\DiagnosticReport;
use Carbon\Carbon;

class DiagnosticReportRepository implements DiagnosticReportInterface
{
    private $model;
    public function __construct(
        DiagnosticReport $diagnosticReportModel
    ) {
        $this->model = $diagnosticReportModel;
    }
    public function getQuery()
    {
        return $this->model->query()->where('procedure', 'lab');
    }

    public function getDataDiagnosticReportByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)->where('procedure', 'lab')->orderBy('id', 'asc')->get();
    }


    public function updateDataBundleDiagnosticReportJob($param = [])
    {
        $data = $this->model
            ->where('encounter_original_code', $param['encounter_original_code'])
            ->where('procedure', 'lab')
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

    public function getDataDiagnosticReportFind($id)
    {
        return $this->model->find($id);
    }
    public function updateStatusDiagnosticReport($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->where('id', $id)
            ->update([
                'satusehat_id_diagnostic_report' => $satusehat_id,
                'satusehat_request_diagnostic_report' => $request,
                'satusehat_response_diagnostic_report' => $response,
                'satusehat_send_diagnostic_report' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode_diagnostic_report' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date_diagnostic_report' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
}

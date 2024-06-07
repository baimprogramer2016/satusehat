<?php

namespace App\Http\Controllers;

use App\Jobs\DiagnosticReportJob;
use App\Repositories\DiagnosticReport\DiagnosticReportInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\ServiceRequest\ServiceRequestInterface;
use App\Traits\ApiTrait;
use App\Traits\GeneralTrait;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class DiagnosticReportController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    private $diagnostic_report_repo, $parameter_repo;
    public $job_logs_repo;
    protected $job_id = 0;


    public function __construct(
        JobLogsInterface $jobLogsInterface,
        DiagnosticReportInterface $diagnosticReportInterface,
        ParameterInterface $parameterInterface
    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->diagnostic_report_repo = $diagnosticReportInterface;
        $this->parameter_repo = $parameterInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->diagnostic_report_repo->getQuery($request->all());

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_diagnostic_report) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_diagnostic_report->satusehat_send_diagnostic_report == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_diagnostic_report->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('identifier', function ($item_diagnostic_report) {
                    $status = '<td>' . $item_diagnostic_report->identifier_1 . '|' . $item_diagnostic_report->procedure_code_original . '</td>';

                    return $status;
                })
                ->addColumn('action', function ($item_diagnostic_report) {


                    if ($item_diagnostic_report->satusehat_send_diagnostic_report == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_diagnostic_report->satusehat_id_diagnostic_report) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_diagnostic_report->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_kirim_ss .
                        $li_response_ss
                        . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['identifier', 'status', 'action'])
                ->make(true);
        }

        return view("pages.diagnostic-report.diagnostic-report");
    }
    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/DiagnosticReport', $id);
            return view('pages.diagnostic-report.diagnostic-report-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function modalKirimSS(Request $request, $id)
    {
        try {
            return view('pages.diagnostic-report.diagnostic-report-kirim-ss', [
                "data_diagnostic_report" => $this->diagnostic_report_repo->getDataDiagnosticReportFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function kirimSS(Request $request)
    {
        try {
            # untuk procedure bukan ID tapi encounter_original_code
            $data_service_request = $this->diagnostic_report_repo->getDataDiagnosticReportFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_service_request['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else if (empty($data_service_request['satusehat_id'])) { # service request harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_service_request_no')
                ];
                return json_encode($result);
            } else if (empty($data_service_request['satusehat_id_specimen'])) { # specimen harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_specimen_no')
                ];
                return json_encode($result);
            } else if (empty($data_service_request['r_observation']['satusehat_id'])) { # observation harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_observation_no')
                ];
                return json_encode($result);
            } else {

                $payload_diagnostic_report = $this->bodyManualDiagnosticReport($data_service_request, $data_parameter);

                $response = $this->post_general_ss('/DiagnosticReport', $payload_diagnostic_report);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->diagnostic_report_repo->updateStatusDiagnosticReport($this->dec($request->id), $satusehat_id, $payload_diagnostic_report, $response);
                    }
                }
                # update status ke database
                return $response;
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function runJob(Request $request, $param_id_jadwal)
    {

        // return $this->diagnostic_report_repo->getDataDiagnosticReportReadyJob();
        try {
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->diagnostic_report_repo->getDataDiagnosticReportReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    DiagnosticReportJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->diagnostic_report_repo,
                    );
                }
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

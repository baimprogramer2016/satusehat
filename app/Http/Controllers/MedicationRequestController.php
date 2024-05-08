<?php

namespace App\Http\Controllers;

use App\Jobs\MedicationRequestJob;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Medication\MedicationInterface;
use App\Repositories\MedicationRequest\MedicationRequestInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class MedicationRequestController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $medication_request_repo, $parameter_repo, $medication_repo, $job_logs_repo;
    protected $job_id = 0;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        MedicationRequestInterface $medicationRequestInterface,
        ParameterInterface $parameterInterface,
        MedicationInterface $medicationInterface

    ) {
        $this->medication_request_repo = $medicationRequestInterface;
        $this->job_logs_repo = $jobLogsInterface;
        $this->parameter_repo = $parameterInterface;
        $this->medication_repo = $medicationInterface;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->medication_request_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_medication_request) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_medication_request->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_medication_request->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_medication_request) {


                    if ($item_medication_request->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_medication_request->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_medication_request->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view("pages.medication-request.medication-request");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/MedicationRequest', $id);
            return view('pages.medication-request.medication-request-response-ss', [
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
            return view('pages.medication-request.medication-request-kirim-ss', [
                "data_medication_request" => $this->medication_request_repo->getDataMedicationRequestFind($this->dec($id)),
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
            $data_medication_request = $this->medication_request_repo->getDataMedicationRequestFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_medication_request['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                # sekali kirim 2 , medication & Mediaction Request
                # 1 tembak medication
                $payload_medication = $this->bodyManualMedication($data_medication_request, $data_parameter);

                $response = $this->post_general_ss('/Medication', $payload_medication);
                $body_parse = json_decode($response->body());

                $satusehat_id_medication = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id_medication = null;
                    } else {
                        $satusehat_id_medication = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->medication_request_repo->updateStatusMedication($this->dec($request->id), $satusehat_id_medication, $payload_medication, $response);
                    }
                }

                # 2 tembak medication request
                $payload_medication_request = $this->bodyManualMedicationRequest($data_medication_request, $data_parameter, $satusehat_id_medication);

                $response = $this->post_general_ss('/MedicationRequest', $payload_medication_request);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->medication_request_repo->updateStatusMedicationRequest($this->dec($request->id), $satusehat_id, $payload_medication_request, $response);
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
        try {
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->medication_request_repo->getDataMedicationRequestReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    MedicationRequestJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->medication_request_repo,
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

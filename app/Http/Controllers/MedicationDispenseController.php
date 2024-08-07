<?php

namespace App\Http\Controllers;

use App\Jobs\MedicationDispenseJob;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\MedicationDispense\MedicationDispenseInterface;
use App\Repositories\MedicationRequest\MedicationRequestInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class MedicationDispenseController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    private $medication_dispense_repo, $medication_request_repo, $parameter_repo;
    public $job_logs_repo;
    protected $job_id = 0;
    public function __construct(
        JobLogsInterface $jobLogsInterface,
        MedicationDispenseInterface $medicationDispenseInterface,
        ParameterInterface $parameterInterface,
        MedicationRequestInterface $medicationRequestInterface

    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->medication_dispense_repo = $medicationDispenseInterface;
        $this->parameter_repo = $parameterInterface;
        $this->medication_request_repo = $medicationRequestInterface;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->medication_dispense_repo->getQuery($request->all());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_medication_dispense) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_medication_dispense->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_medication_dispense->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_medication_dispense) {


                    if ($item_medication_dispense->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_medication_dispense->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_medication_dispense->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.medication-dispense.medication-dispense");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/MedicationDispense', $id);
            return view('pages.medication-dispense.medication-dispense-response-ss', [
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
            return view('pages.medication-dispense.medication-dispense-kirim-ss', [
                "data_medication_dispense" => $this->medication_dispense_repo->getDataMedicationDispenseFind($this->dec($id)),
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
            $data_medication_dispense = $this->medication_dispense_repo->getDataMedicationDispenseFind($this->dec($request->id));
            $data_medication_request = $this->medication_request_repo->getDataMedicationRequestIdentifier($data_medication_dispense['encounter_original_code'], $data_medication_dispense['identifier_1'], $data_medication_dispense['identifier_2']);
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            # encounter harus dikirim terlebih dahulu
            if (empty($data_medication_request['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else if (empty($data_medication_request['satusehat_id'])) { # medication request harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_medication_request_no')
                ];
                return json_encode($result);
            } else {
                # 2 tembak medication request
                $payload_medication_dispense = $this->bodyManualMedicationDispense($data_medication_dispense, $data_medication_request, $data_parameter);

                $response = $this->post_general_ss('/MedicationDispense', $payload_medication_dispense);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->medication_dispense_repo->updateStatusMedicationDispense($this->dec($request->id), $satusehat_id, $payload_medication_dispense, $response);
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
            if ($this->medication_dispense_repo->getDataMedicationDispenseReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    MedicationDispenseJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->medication_dispense_repo,
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

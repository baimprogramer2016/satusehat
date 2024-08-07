<?php

namespace App\Http\Controllers;

use App\Jobs\PrognosisJob;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Prognosis\PrognosisInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class PrognosisController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $prognosis_repo, $encounter_repo, $parameter_repo, $condition_repo;
    public $job_logs_repo;
    protected $job_id = 0;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        PrognosisInterface $prognosisInterface,
        EncounterInterface $encounterInterface,
        ParameterInterface $parameterInterface,
        ConditionInterface $conditionInterface,

    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->prognosis_repo = $prognosisInterface;
        $this->encounter_repo = $encounterInterface;
        $this->parameter_repo = $parameterInterface;
        $this->condition_repo = $conditionInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->prognosis_repo->getQuery($request->all());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_prognosis) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_prognosis->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_prognosis->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_prognosis) {


                    if ($item_prognosis->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_prognosis->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_prognosis->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.prognosis.prognosis");
    }

    public function modalKirimSS(Request $request, $id)
    {
        try {
            return view('pages.prognosis.prognosis-kirim-ss', [
                "data_prognosis" => $this->prognosis_repo->getDataPrognosisFind($this->dec($id)),
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
            $data_prognosis = $this->prognosis_repo->getDataPrognosisFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();
            $data_condition = $this->condition_repo->getDataConditionByOriginalCodeSend($data_prognosis['encounter_original_code']);


            if (empty($data_prognosis['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_prognosis = $this->bodyManualPrognosis($data_prognosis, $data_parameter, $data_condition);


                $response = $this->post_general_ss('/ClinicalImpression', $payload_prognosis);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->prognosis_repo->updateStatusPrognosis($this->dec($request->id), $satusehat_id, $payload_prognosis, $response);
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

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/ClinicalImpression', $id);
            return view('pages.prognosis.prognosis-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function runJob(Request $request, $param_id_jadwal)
    {
        try {


            // $item_data = $this->prognosis_repo->getDataPrognosisReadyJob()[0];
            // $data_condition = $this->condition_repo->getDataConditionByOriginalCode($item_data['encounter_original_code']);
            // $payload_prognosis = $this->bodyManualPrognosis($item_data, $this->parameter_repo->getDataParameterFirst(), $data_condition);
            // return $payload_prognosis;
            // return $this->prognosis_repo->getDataPrognosisReadyJob();
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->prognosis_repo->getDataPrognosisReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    PrognosisJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->prognosis_repo,
                        $this->condition_repo,
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

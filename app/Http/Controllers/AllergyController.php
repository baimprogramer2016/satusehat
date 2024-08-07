<?php

namespace App\Http\Controllers;

use App\Jobs\AllergyJob;
use App\Repositories\Allergy\AllergyInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class AllergyController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $allergy_repo, $encounter_repo, $parameter_repo;
    public $job_logs_repo;
    protected $job_id = 0;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        AllergyInterface $allergyInterface,
        EncounterInterface $encounterInterface,
        ParameterInterface $parameterInterface

    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->allergy_repo = $allergyInterface;
        $this->encounter_repo = $encounterInterface;
        $this->parameter_repo = $parameterInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->allergy_repo->getQuery($request->all());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_allergy) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_allergy->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_allergy->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_allergy) {


                    if ($item_allergy->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_allergy->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_allergy->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.allergy.allergy");
    }

    public function modalKirimSS(Request $request, $id)
    {
        try {
            return view('pages.allergy.allergy-kirim-ss', [
                "data_allergy" => $this->allergy_repo->getDataAllergyFind($this->dec($id)),
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
            $data_allergy = $this->allergy_repo->getDataAllergyFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_allergy['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_allergy = $this->bodyManualAllergy($data_allergy, $data_parameter);

                //return $payload_allergy;
                $response = $this->post_general_ss('/AllergyIntolerance', $payload_allergy);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->allergy_repo->updateStatusAllergy($this->dec($request->id), $satusehat_id, $payload_allergy, $response);
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
            $response_satusehat  = $this->api_response_ss('/AllergyIntolerance', $id);
            return view('pages.allergy.allergy-response-ss', [
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


            // $item_data = $this->composition_repo->getDataCompositionReadyJob()[0];
            // $payload_composition = $this->bodyManualComposition($item_data, $this->parameter_repo->getDataParameterFirst());
            // return $payload_composition;
            # return $this->composition_repo->getDataCompositionReadyJob();
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->allergy_repo->getDataAllergyReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    AllergyJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->allergy_repo,
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

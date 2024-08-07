<?php

namespace App\Http\Controllers;

use App\Jobs\ConditionJob;
use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\MasterIcd10\MasterIcd10Interface;
use Illuminate\Http\Request;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Parameter\ParameterInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Throwable;


class ConditionController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $condition_repo, $encounter_repo, $master_icd_10_repo, $parameter_repo;
    private $jumlah_form = [0, 1, 2, 3, 4];

    public $job_logs_repo;
    protected $job_id = 0;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        ConditionInterface $conditionInterface,
        EncounterInterface $encounterInterface,
        MasterIcd10Interface $masterIcd10Interface,
        ParameterInterface $parameterInterface
    ) {
        $this->job_logs_repo = $jobLogsInterface;
        $this->condition_repo = $conditionInterface;
        $this->encounter_repo = $encounterInterface;
        $this->master_icd_10_repo = $masterIcd10Interface;
        $this->parameter_repo = $parameterInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->condition_repo->getQuery($request->all());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_condition) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_condition->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_condition->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_condition) {


                    if ($item_condition->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_condition->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                        $li_edit_ss = "";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_condition->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                        $li_edit_ss = "<li><a href='" . route('condition-edit', $this->enc($item_condition->id)) . "'><em class='icon ni ni-edit'></em><span>Edit</span></a></li>";
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_edit_ss .
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

        return view("pages.condition.condition");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Condition', $id);
            return view('pages.condition.condition-response-ss', [
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
            return view('pages.condition.condition-kirim-ss', [
                "data_condition" => $this->condition_repo->getDataConditionFind($this->dec($id)),
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
            $data_condition = $this->condition_repo->getDataConditionFind($this->dec($request->id));

            if (empty($data_condition['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_condition = $this->bodyManualCondition($data_condition);

                $response = $this->post_general_ss('/Condition', $payload_condition);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->condition_repo->updateStatusCondition($this->dec($request->id), $satusehat_id, $payload_condition, $response);
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


    public function formTambah(Request $request)
    {
        try {
            return view(
                'pages.condition.condition-tambah',
                [
                    "jumlah_form" => $this->jumlah_form
                ]
            );
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    function saveCondition(Request $request)
    {
        // return $request->all();
        $request->validate([
            'encounter_original_code' => 'required|exists:ss_encounter,original_code',
            'code_icd_display.0' => 'required',
            'onset_datetime.0' => 'required',
            'onset_datetime_hour.0' => 'required',
            'rank.0' => 'required',

        ]);

        try {

            $data_insert = [];
            $jml_form = count($request->code_icd_display);

            $data_encounter = $this->encounter_repo->getDataEncounterByOriginalCode($request->encounter_original_code);
            for ($i = 0; $i < $jml_form; $i++) {

                //jika kosong break;
                if ($request->code_icd_display[$i] == '') {
                    break;
                }

                $code_icd_split = explode(" # ", $request->code_icd_display[$i]);
                $insert['encounter_original_code'] = $request->encounter_original_code;
                $insert['rank'] = $request->rank[$i];
                $insert['clinical_code'] = 'active';
                $insert['clinical_display'] = 'Active';
                $insert['category_code'] = 'encounter-diagnosis';
                $insert['category_display'] = 'Encounter Diagnosis';
                $insert['code_icd'] = $code_icd_split[0];
                $insert['code_icd_display'] = $code_icd_split[1];
                $insert['subject_reference'] = $data_encounter->subject_reference;
                $insert['subject_display'] = $data_encounter->subject_display;
                $insert['encounter_display'] = '-';
                $insert['onset_datetime'] = $this->formatDate2($request->onset_datetime[$i], $request->onset_datetime_hour[$i]);
                $insert['record_date'] = $this->formatDate2($request->onset_datetime[$i], $request->onset_datetime_hour[$i]);
                $insert['satusehat_send'] = 4;
                $insert['uuid'] = $this->getUUID();

                array_push($data_insert, $insert);
            }

            $this->condition_repo->storeCondition($data_insert);

            return redirect('condition-tambah')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function searchIcd10(Request $request, $id)
    {


        $data_result = $this->master_icd_10_repo->getQuery($id);

        $tampung = [];
        foreach ($data_result as $item_result) {
            array_push($tampung, $item_result->code2 . ' # ' . $item_result->description);
        }
        return $tampung;
    }
    public function formEdit(Request $request, $id)
    {
        try {
            return view(
                'pages.condition.condition-edit',
                [
                    "data_condition" => $this->condition_repo->getDataConditionFind($this->dec($id))
                ]
            );
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    function updateCondition(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'encounter_original_code' => 'required|exists:ss_encounter,original_code',
            'code_icd_display' => 'required',
            'onset_datetime' => 'required',
            'onset_datetime_hour' => 'required',
            'rank' => 'required',
        ]);

        try {
            $code_icd_split = explode(" # ", $request->code_icd_display);
            $insert['encounter_original_code'] = $request->encounter_original_code;
            $insert['rank'] = $request->rank;
            $insert['code_icd'] = $code_icd_split[0];
            $insert['code_icd_display'] = $code_icd_split[1];

            $insert['onset_datetime'] = $this->formatDate2($request->onset_datetime, $request->onset_datetime_hour);
            $insert['record_date'] = $this->formatDate2($request->onset_datetime, $request->onset_datetime_hour);
            $insert['satusehat_send'] = 4;

            $this->condition_repo->updateCondition($insert, $id);

            return redirect('condition')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    public function runJob(Request $request, $param_id_jadwal)
    {
        try {
            // $item_data = $this->condition_repo->getDataConditionReadyJob()[0];
            // $payload_composition = $this->bodyManualCondition($item_data);
            // return $payload_composition;
            # return $this->composition_repo->getDataCompositionReadyJob();
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->condition_repo->getDataConditionReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    ConditionJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
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

    //
}

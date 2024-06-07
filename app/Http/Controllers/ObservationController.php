<?php

namespace App\Http\Controllers;

use App\Jobs\ObservationJob;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;

use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Models\Observation;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\Parameter\ParameterInterface;
use Yajra\DataTables\Facades\Datatables;

use Throwable;

class ObservationController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $observation_repo, $encounter_repo;
    public $job_logs_repo, $parameter_repo;
    protected $job_id = 0;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        ParameterInterface $parameterInterface,
        ObservationInterface $observationInterface,
        EncounterInterface $encounterInterface
    ) {
        $this->parameter_repo = $parameterInterface;
        $this->job_logs_repo = $jobLogsInterface;
        $this->observation_repo = $observationInterface;
        $this->encounter_repo = $encounterInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->observation_repo->getQuery($request->all());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_observation) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_observation->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_observation->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_observation) {


                    if ($item_observation->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_observation->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                        $li_edit_ss = "";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal'  onClick=modalKirimSS('" . $this->enc($item_observation->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                        $li_edit_ss = "<li><a href='" . route('observation-edit', $this->enc($item_observation->id)) . "'><em class='icon ni ni-edit'></em><span>Edit</span></a></li>";
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

        return view("pages.observation.observation");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Observation', $id);
            return view('pages.observation.observation-response-ss', [
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
            return view('pages.observation.observation-kirim-ss', [
                "data_observation" => $this->observation_repo->getDataObservationFind($this->dec($id)),
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
            $data_observation = $this->observation_repo->getDataObservationFind($this->dec($request->id));

            if (empty($data_observation['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_observation = $this->bodyManualObservation($data_observation);

                $response = $this->post_general_ss('/Observation', $payload_observation);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->observation_repo->updateStatusObservation($this->dec($request->id), $satusehat_id, $payload_observation, $response);
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
                'pages.observation.observation-tambah',
                [
                    "data_type_observation" => $this->typeObservation()
                ]
            );
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    function saveObservation(Request $request)
    {
        // return $request->all();
        $request->validate([
            'encounter_original_code' => 'required|exists:ss_encounter,original_code',
            'effective_datetime.0' => 'required',
            'effective_datetime_hour.0' => 'required',
            'quantity_value.0' => 'required',
        ]);

        try {


            $data_insert = [];
            $jml_form = count($request->type_observation);

            $data_encounter = $this->encounter_repo->getDataEncounterByOriginalCode($request->encounter_original_code);
            for ($i = 0; $i < $jml_form; $i++) {

                $insert['encounter_original_code'] = $request->encounter_original_code;
                $insert['type_observation'] = $request->type_observation[$i];
                $insert['status'] = 'final';
                $insert['category_code'] = 'vital-signs';
                $insert['category_display'] = 'Vital Signs';
                $insert['code_observation'] = $request->code_observation[$i];
                $insert['code_display'] = $request->code_display[$i];
                $insert['subject_reference'] = $data_encounter->subject_reference;
                $insert['subject_display'] = $data_encounter->subject_display;
                $insert['performer_reference'] = $data_encounter->participant_individual_reference;
                $insert['encounter_display'] = '-';
                $insert['effective_datetime'] = $this->formatDate2($request->effective_datetime[$i], $request->effective_datetime_hour[$i]);
                $insert['issued'] = $this->formatDate2($request->effective_datetime[$i], $request->effective_datetime_hour[$i]);
                $insert['quantity_value'] = $request->quantity_value[$i];
                $insert['quantity_unit'] = $request->quantity_unit[$i];
                $insert['quantity_code'] = $request->quantity_code[$i];
                $insert['satusehat_send'] = 4;
                $insert['uuid'] = $this->getUUID();

                array_push($data_insert, $insert);
            }


            $this->observation_repo->storeObservation($data_insert);

            return redirect('observation-tambah')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function formEdit(Request $request, $id)
    {
        try {
            return view(
                'pages.observation.observation-edit',
                [
                    "data_observation" => $this->observation_repo->getDataObservationFind($this->dec($id))
                ]
            );
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    function updateObservation(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'encounter_original_code' => 'required|exists:ss_encounter,original_code',
            'effective_datetime' => 'required',
            'effective_datetime_hour' => 'required',
            'quantity_value' => 'required',
        ]);

        try {
            $insert['encounter_original_code'] = $request->encounter_original_code;
            $insert['quantity_value'] = $request->quantity_value;
            $insert['effective_datetime'] = $this->formatDate2($request->effective_datetime, $request->effective_datetime_hour);
            $insert['issued'] = $this->formatDate2($request->effective_datetime, $request->effective_datetime_hour);
            $insert['satusehat_send'] = 4;

            $this->observation_repo->updateObservation($insert, $id);

            return redirect('observation')
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
            # Jalankan Job
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if ($this->observation_repo->getDataObservationReadyJob()->count() > 0) {
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    ObservationJob::dispatch(
                        $this->parameter_repo,
                        $this->job_logs_repo,
                        $this->job_id,
                        $this->observation_repo,
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

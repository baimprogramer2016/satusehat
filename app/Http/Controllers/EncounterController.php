<?php

namespace App\Http\Controllers;

use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\Patient\PatientInterface;
use App\Repositories\Practitioner\PractitionerInterface;
use App\Repositories\Procedure\ProcedureInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Throwable;
use Ramsey\Uuid\Uuid;

class EncounterController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    private $encounter_repo;

    private $condition_repo;
    private $observation_repo;
    private $procedure_repo;
    private $parameter_repo;
    private $patient_repo;
    private $practitioner_repo;


    public function __construct(
        EncounterInterface $encounterInterface,
        ConditionInterface $conditionInterface,
        ObservationInterface $observationInterface,
        ProcedureInterface $procedureInterface,
        ParameterInterface $parameterInterface,
        PatientInterface $patientInterface,
        PractitionerInterface $practitionerInterface,
    ) {
        $this->encounter_repo = $encounterInterface;
        $this->condition_repo = $conditionInterface;
        $this->observation_repo = $observationInterface;
        $this->procedure_repo = $procedureInterface;
        $this->parameter_repo = $parameterInterface;
        $this->patient_repo = $patientInterface;
        $this->practitioner_repo = $practitionerInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->encounter_repo->getQuery($request->all());

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($item_encounter) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_encounter->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_encounter->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_encounter) {

                    $li_detail_encounter = "<li><a href='#file-upload' data-toggle='modal' onClick=modalDetail('" . $this->enc($item_encounter->original_code) . "')><em class='icon ni ni-search'></em><span>Detail</span></a></li>";
                    if ($item_encounter->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_encounter->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                        $li_update_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUpdateSS('" . $this->enc($item_encounter->id) . "')><em class='icon ni ni-update'></em><span>Update Encounter Satu Sehat</span></a></li>";
                        $li_edit_ss = "";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_encounter->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                        $li_update_ss = '';
                        $li_edit_ss = "<li><a href='" . route('encounter-edit', $this->enc($item_encounter->id)) . "'><em class='icon ni ni-edit'></em><span>Edit</span></a></li>";
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .

                        $li_detail_encounter .
                        $li_kirim_ss .
                        $li_response_ss .
                        $li_update_ss .
                        $li_edit_ss
                        . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view("pages.encounter.encounter");
    }

    public function detail(Request $request, $original_code)
    {
        try {
            return view('pages.encounter.encounter-detail', [
                'data_condition' => $this->condition_repo->getDataConditionByOriginalCode($this->dec($original_code)),
                'data_observation' => $this->observation_repo->getDataObservationByOriginalCode($this->dec($original_code)),
                'data_procedure' => $this->procedure_repo->getDataProcedureByOriginalCode($this->dec($original_code))
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Encounter', $id);
            return view('pages.encounter.encounter-response-ss', [
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
            return view('pages.encounter.encounter-kirim-ss', [
                "data_encounter" => $this->encounter_repo->getDataEncounterFind($this->dec($id)),
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
            $data_encounter = $this->encounter_repo->getDataEncounterFind($this->dec($request->id));

            $payload_encounter = $this->bodyManualEncounter($this->parameter_repo->getDataParameterFirst(), $data_encounter);
            // return json_encode($payload_encounter);
            $response = $this->post_general_ss('/Encounter', $payload_encounter);
            $body_parse = json_decode($response->body());

            $satusehat_id = null;
            if ($response->successful()) {
                # jika sukses tetapi hasil gagal
                if ($body_parse->resourceType == 'OperationOutcome') {
                    $satusehat_id = null;
                } else {
                    $satusehat_id = $body_parse->id;
                    $this->encounter_repo->updateStatusEncounter($this->dec($request->id), $satusehat_id, $payload_encounter, $response);
                }
            }
            # update status ke database
            return $response;
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function modalUpdateSS(Request $request, $id)
    {
        try {
            return view('pages.encounter.encounter-update-ss', [
                "data_encounter" => $this->encounter_repo->getDataEncounterFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function updateSS(Request $request)
    {
        try {
            $data_encounter = $this->encounter_repo->getDataEncounterFind($this->dec($request->id));

            $payload_encounter = $this->bodyManualEncounterUpdate($this->parameter_repo->getDataParameterFirst(), $data_encounter);

            $response = $this->put_general_ss('/Encounter/' . $data_encounter->satusehat_id, $payload_encounter);

            $body_parse = json_decode($response->body());

            $satusehat_id = null;
            if ($response->successful()) {
                # jika sukses tetapi hasil gagal
                if ($body_parse->resourceType == 'OperationOutcome') {
                    $satusehat_id = null;
                } else {
                    $satusehat_id = $body_parse->id;
                    # hanya jika sukses baru update status
                    $this->encounter_repo->updateStatusEncounter($this->dec($request->id), $satusehat_id, $payload_encounter, $response);
                }
            }
            return  $response;
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function formTambah(Request $request, LocationInterface $locationInterface)
    {

        try {
            return view('pages.encounter.encounter-tambah', [
                "data_location" => $locationInterface->getDataLocationReadySatuSehat(),
                "jenis_rawat" => $this->jenisRawat()
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function checkNik(Request $request)
    {
        try {

            if ($request['pa'] == 'patient') {
                $data = $this->patient_repo->getDataPatientOriginalCode($request->id_pasien);
            } else {
                $data = $this->practitioner_repo->getDataPractitionerOriginalCode($request->id_praktisi);
            }
            if (empty($data)) {
                $result['id_ihs'] = '';
                $result['nik'] = "";
                $result['nama'] = "";
                $result['color'] = "bg-danger text-white";
            } else {
                $result['id_ihs'] = $data['satusehat_id'];
                $result['nik'] =  $data['nik'];
                $result['nama'] =  $data['name'];
                $result['color'] = "bg-success text-white";
            }

            return $result;
        } catch (Throwable $e) {
            return "error";
        }
    }

    function saveEncounter(Request $request)
    {
        $request->validate([
            'original_code' => 'required|unique:ss_encounter,original_code',
            'subject_nik' => 'required',
            'subject_reference' => 'required|exists:ss_patient,satusehat_id',
            'subject_display' => 'required',
            'participant_nik' => 'required',
            'participant_individual_reference' => 'required|exists:ss_practitioner,satusehat_id',
            'participant_individual_display' => 'required',
            'location_reference' => 'required',
            'period_start' => 'required',
            'period_start_hour' => 'required',
            'period_start_minute' => 'required',
            'period_end' => 'required',
            'period_end_hour' => 'required',
            'period_end_minute' => 'required',
            'status_history_arrived_status' => 'required',
            'status_history_arrived_start' => 'required',
            'status_history_arrived_hour' => 'required',
            'status_history_arrived_minute' => 'required',
            'status_history_inprogress_status' => 'required',
            'status_history_inprogress_start' => 'required',
            'status_history_inprogress_hour' => 'required',
            'status_history_inprogress_minute' => 'required',
            'status_history_finished_status' => 'required',
            'status_history_finished_start' => 'required',
            'status_history_finished_hour' => 'required',
            'status_history_finished_minute' => 'required',
        ]);

        try {
            $this->encounter_repo->storeEncounter($request->all());

            return redirect('encounter-tambah')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }

        //simpan
    }
    public function formEdit(Request $request, LocationInterface $locationInterface, $id)
    {
        try {
            $data_encounter = $this->encounter_repo->getDataEncounterFind($this->dec($id));
            return view('pages.encounter.encounter-edit', [
                "data_encounter" => $data_encounter,
                "data_location" => $locationInterface->getDataLocationReadySatuSehat(),
                "jenis_rawat" => $this->jenisRawat(),
                "id_ubah" => $this->enc($data_encounter->id)
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function updateEncounter(Request $request, $id)
    {
        $request->validate([
            'original_code' => 'required|exists:ss_encounter,original_code',
            'subject_nik' => 'required',
            'subject_reference' => 'required|exists:ss_patient,satusehat_id',
            'subject_display' => 'required',
            'participant_nik' => 'required',
            'participant_individual_reference' => 'required|exists:ss_practitioner,satusehat_id',
            'participant_individual_display' => 'required',
            'location_reference' => 'required',
            'period_start' => 'required',
            'period_start_hour' => 'required',
            'period_start_minute' => 'required',
            'period_end' => 'required',
            'period_end_hour' => 'required',
            'period_end_minute' => 'required',
            'status_history_arrived_status' => 'required',
            'status_history_arrived_start' => 'required',
            'status_history_arrived_hour' => 'required',
            'status_history_arrived_minute' => 'required',
            'status_history_inprogress_status' => 'required',
            'status_history_inprogress_start' => 'required',
            'status_history_inprogress_hour' => 'required',
            'status_history_inprogress_minute' => 'required',
            'status_history_finished_status' => 'required',
            'status_history_finished_start' => 'required',
            'status_history_finished_hour' => 'required',
            'status_history_finished_minute' => 'required',
        ]);

        try {
            $this->encounter_repo->updateEncounter($request->all(), $this->dec($id));

            return redirect('encounter')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

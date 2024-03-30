<?php

namespace App\Http\Controllers;

use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\MasterIcd10\MasterIcd10Interface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Throwable;


class ConditionControlller extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $condition_repo, $encounter_repo, $master_icd_10_repo;
    private $jumlah_form = [0, 1, 2, 3, 4];

    public function __construct(
        ConditionInterface $conditionInterface,
        EncounterInterface $encounterInterface,
        MasterIcd10Interface $masterIcd10Interface,


    ) {
        $this->condition_repo = $conditionInterface;
        $this->encounter_repo = $encounterInterface;
        $this->master_icd_10_repo = $masterIcd10Interface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->condition_repo->getQuery();
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
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_condition->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

    //
}

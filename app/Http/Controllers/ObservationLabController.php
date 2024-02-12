<?php

namespace App\Http\Controllers;

use App\Repositories\ObservationLab\ObservationLabInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\ApiTrait;
use App\Traits\GeneralTrait;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ObservationLabController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    private $observation_lab_repo, $parameter_repo;

    public function __construct(
        ObservationLabInterface $observationLabInterface,
        ParameterInterface $parameterInterface
    ) {
        $this->observation_lab_repo = $observationLabInterface;
        $this->parameter_repo = $parameterInterface;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->observation_lab_repo->getObservationLabQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_observation_lab) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_observation_lab->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_observation_lab->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_observation_lab) {

                    if ($item_observation_lab->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_observation_lab->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_observation_lab->uuid) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.observation-lab.observation-lab");
    }
    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Observation', $id);
            return view('pages.observation-lab.observation-lab-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function modalKirimSS(Request $request, $uuid) //dengan uuid
    {
        try {
            return view('pages.observation-lab.observation-lab-kirim-ss', [
                "data_observation" => $this->observation_lab_repo->getDataObservationLabFind($this->dec($uuid)), //data dari service request
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
            $data_observation = $this->observation_lab_repo->getDataObservationLabFind($this->dec($request->uuid));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_observation['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else if (empty($data_observation['satusehat_id'])) { # service request harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_service_request_no')
                ];
                return json_encode($result);
            } else if (empty($data_observation['satusehat_id_specimen'])) { # specimen harus dikirim terlebih dahulu
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_specimen_no')
                ];
                return json_encode($result);
            } else {

                $payload_observation = $this->bodyManualObservationLab($data_observation, $data_parameter);
                // return $payload_observation;
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
                        $this->observation_lab_repo->updateStatusObservationLab($this->dec($request->uuid), $satusehat_id, $payload_observation, $response);
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
}

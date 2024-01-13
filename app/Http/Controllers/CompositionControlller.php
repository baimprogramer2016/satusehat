<?php

namespace App\Http\Controllers;

use App\Repositories\Composition\CompositionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;


class CompositionControlller extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;
    private $composition_repo, $encounter_repo, $parameter_repo;

    public function __construct(
        CompositionInterface $compositionInterface,
        EncounterInterface $encounterInterface,
        ParameterInterface $parameterInterface

    ) {
        $this->composition_repo = $compositionInterface;
        $this->encounter_repo = $encounterInterface;
        $this->parameter_repo = $parameterInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->composition_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_composition) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_composition->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_composition->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_composition) {


                    if ($item_composition->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_composition->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_composition->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.composition.composition");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/Composition', $id);
            return view('pages.composition.composition-response-ss', [
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
            return view('pages.composition.composition-kirim-ss', [
                "data_composition" => $this->composition_repo->getDataCompositionFind($this->dec($id)),
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
            $data_composition = $this->composition_repo->getDataCompositionFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_composition['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_composition = $this->bodyManualComposition($data_composition, $data_parameter);

                $response = $this->post_general_ss('/Composition', $payload_composition);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->composition_repo->updateStatusComposition($this->dec($request->id), $satusehat_id, $payload_composition, $response);
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

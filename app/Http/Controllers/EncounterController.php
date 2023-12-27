<?php

namespace App\Http\Controllers;

use App\Repositories\Condition\ConditionInterface;
use App\Repositories\Encounter\EncounterInterface;
use App\Repositories\Observation\ObservationInterface;
use App\Repositories\Procedure\ProcedureInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class EncounterController extends Controller
{
    use GeneralTrait;
    use ApiTrait;

    private $encounter_repo;

    private $condition_repo;
    private $observation_repo;
    private $procedure_repo;
    public function __construct(
        EncounterInterface $encounterInterface,
        ConditionInterface $conditionInterface,
        ObservationInterface $observationInterface,
        ProcedureInterface $procedureInterface,
    ) {
        $this->encounter_repo = $encounterInterface;
        $this->condition_repo = $conditionInterface;
        $this->observation_repo = $observationInterface;
        $this->procedure_repo = $procedureInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->encounter_repo->getQuery();
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
                    } else {
                        $li_kirim_ss = "<li><a  onClick=modalKirimSS('" . $this->enc($item_encounter->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
                        $li_response_ss = '';
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .

                        $li_detail_encounter .
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
}

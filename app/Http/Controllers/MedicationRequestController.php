<?php

namespace App\Http\Controllers;

use App\Repositories\MedicationRequest\MedicationRequestInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;

class MedicationRequestController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    private $medication_request_repo;

    public function __construct(
        MedicationRequestInterface $medicationRequestInterface,

    ) {
        $this->medication_request_repo = $medicationRequestInterface;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->medication_request_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_medication_request) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_medication_request->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_medication_request->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('action', function ($item_medication_request) {


                    if ($item_medication_request->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_medication_request->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a  onClick=modalKirimSS('" . $this->enc($item_medication_request->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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

        return view("pages.medication-request.medication-request");
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/MedicationRequest', $id);
            return view('pages.medication-request.medication-request-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\ServiceRequest\ServiceRequestInterface;
use App\Traits\ApiTrait;
use App\Traits\GeneralTrait;
use App\Traits\JsonTrait;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ServiceRequestController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    private $service_request_repo, $parameter_repo;

    public function __construct(
        ServiceRequestInterface $serviceRequestInterface,
        ParameterInterface $parameterInterface
    ) {
        $this->service_request_repo = $serviceRequestInterface;
        $this->parameter_repo = $parameterInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->service_request_repo->getQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($item_service_request) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $clr = 'text-warning';
                    if ($item_service_request->satusehat_send == 1) {
                        $clr = 'text-success';
                    }
                    $status = '<td><span class=' . $clr . '>' . $item_service_request->r_status->description ?? '' . '</span></td>';

                    return $status;
                })
                ->addColumn('identifier', function ($item_service_request) {
                    $status = '<td>' . $item_service_request->identifier_1 . '|' . $item_service_request->procedure_code_original . '</td>';

                    return $status;
                })
                ->addColumn('action', function ($item_service_request) {


                    if ($item_service_request->satusehat_send == 1) {
                        $li_kirim_ss = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_service_request->satusehat_id) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_kirim_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalKirimSS('" . $this->enc($item_service_request->id) . "')><em class='icon ni ni-send'></em><span>Kirim ke Satu Sehat</span></a></li>";
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
                ->rawColumns(['identifier', 'status', 'action'])
                ->make(true);
        }

        return view("pages.service-request.service-request");
    }
    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('/ServiceRequest', $id);
            return view('pages.service-request.service-request-response-ss', [
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
            return view('pages.service-request.service-request-kirim-ss', [
                "data_service_request" => $this->service_request_repo->getDataServiceRequestFind($this->dec($id)),
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
            $data_service_request = $this->service_request_repo->getDataServiceRequestFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            if (empty($data_service_request['r_encounter']['satusehat_id'])) {
                $result =  [
                    "resourceType" => "OperationOutcome",
                    "message" => config('constan.error_message.error_encounter_no')
                ];
                return json_encode($result);
            } else {

                $payload_service_request = $this->bodyManualServiceRequest($data_service_request, $data_parameter);

                $response = $this->post_general_ss('/ServiceRequest', $payload_service_request);
                $body_parse = json_decode($response->body());

                $satusehat_id = null;
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->service_request_repo->updateStatusServiceRequest($this->dec($request->id), $satusehat_id, $payload_service_request, $response);
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

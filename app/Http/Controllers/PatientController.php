<?php

namespace App\Http\Controllers;

use App\Jobs\PatientJob;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Patient\PatientInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    public $patient_repo;
    public $job_logs_repo;
    protected $job_id = 0;
    public function __construct(
        PatientInterface $patientRepository,
        JobLogsInterface $jobLogsInterface
    ) {
        $this->patient_repo = $patientRepository;
        $this->job_logs_repo = $jobLogsInterface;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = $this->patient_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status_update', function ($item_patient) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $status_update = ($item_patient->satusehat_process == 1) ? '<span class=text-success>Berhasil Update</span>' : '';

                    return $status_update;
                })
                ->addColumn('action', function ($item_patient) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    if ($item_patient->satusehat_process == 1) {
                        $li_update = '';
                        $li_update_ihs = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_patient->nik) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_update = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUbah('" . $this->enc($item_patient->id) . "')><em class='icon ni ni-edit'></em><span>Ubah</span></a></li>";
                        $li_update_ihs = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUpdateIhs('" . $this->enc($item_patient->id) . "')><em class='icon ni ni-send'></em><span>Update ID IHS</span></a></li>";
                        $li_response_ss = '';
                    }
                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_update .
                        $li_update_ihs .
                        $li_response_ss
                        . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['status_update', 'action'])
                ->make(true);
        }

        // return $this->patient->getAll();
        return view('pages.patient.patient');
    }


    public function responseSS(Request $request, $id)
    {
        try {
            $endpoint = '/Patient?identifier=https://fhir.kemkes.go.id/id';
            $nik = $this->enc('nik|' . $this->dec($id));
            $response_satusehat  = $this->api_response_ss($endpoint, $nik);
            return view('pages.patient.patient-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function ubah(Request $request, $id)
    {
        try {
            return view('pages.patient.patient-ubah', [
                "data_patient" => $this->patient_repo->getDataPatientFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function update(Request $request)
    {
        try {
            # update
            $this->patient_repo->updatePatient($request->all(), $this->dec($request->id_ubah));

            return redirect('pasien')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    public function ubahIHS(Request $request, $id)
    {
        try {
            $id_ihs = 0;
            $nama_ihs = config('constan.error_message.id_ihs_error');
            $color_ihs = 'danger';

            $data_patient = $this->patient_repo->getDataPatientFind($this->dec($id));
            if (!empty($data_patient['nik'])) {
                $endpoint = '/Patient?identifier=https://fhir.kemkes.go.id/id';
                $nik = $this->enc('nik|' . $data_patient['nik']);
                $response_satusehat  = json_decode($this->api_response_ss($endpoint, $nik));


                if ($response_satusehat->total > 0) {
                    $id_ihs = $response_satusehat->entry[0]->resource->id;
                    $nama_ihs = $response_satusehat->entry[0]->resource->name[0]->text;
                    $color_ihs = 'success';
                }
            }
            return view('pages.patient.patient-ubah-ihs', [
                "data_patient" => $data_patient,
                "id_ihs" => $id_ihs,
                "nama_ihs" => $nama_ihs,
                'color_ihs' => $color_ihs

            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function updateIHS(Request $request)
    {
        try {
            # update
            $param['id'] = $this->dec($request->id_ubah);
            $param['satusehat_id'] = $request->satusehat_id;
            $param['satusehat_process'] = 1;
            $param['satusehat_message'] = null;
            $param['satusehat_statuscode'] = 200;

            $this->patient_repo->updateIhsPatient($param);

            return redirect('pasien')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    # untuk Scheduler dan Manual
    public function runJob(Request $request)
    {
        try {
            # buat job log
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = config('constan.job_name.patient'); //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            if ($this->patient_repo->getDataPatientReadyJob()->count() > 0) {

                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                    return config('constan.error_message.toast_job_already');
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    # jalan kan job
                    PatientJob::dispatch(
                        $this->patient_repo,
                        $this->job_logs_repo,
                        $this->job_id
                    );
                    return config('constan.error_message.toast_job_running');
                }
            } else {
                return config('constan.error_message.toast_job_data_update');
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

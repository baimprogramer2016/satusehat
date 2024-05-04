<?php

namespace App\Http\Controllers;

use App\Jobs\PractitionerJob;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Practitioner\PractitionerInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Throwable;


class PractitionerController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    public $practitioner_repo;
    public $job_logs_repo;
    protected $job_id = 0;
    public function __construct(
        PractitionerInterface $practitionerRepository,
        JobLogsInterface $jobLogsInterface
    ) {
        $this->practitioner_repo = $practitionerRepository;
        $this->job_logs_repo = $jobLogsInterface;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = $this->practitioner_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status_update', function ($item_patient) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $status_update = ($item_patient->satusehat_process == 1) ? '<span class=text-success>Berhasil Update</span>' : '';

                    return $status_update;
                })
                ->addColumn('action', function ($item_practitioner) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_practitioner . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    if ($item_practitioner->satusehat_process == 1) {
                        $li_update = '';
                        $li_update_ihs = '';
                        $li_response_ss = "<li><a href='#file-upload' data-toggle='modal' onClick=modalResponseSS('" . $this->enc($item_practitioner->nik) . "')><em class='icon ni ni-eye'></em><span>Response Satu Sehat</span></a></li>";
                    } else {
                        $li_update = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUbah('" . $this->enc($item_practitioner->id) . "')><em class='icon ni ni-edit'></em><span>Ubah</span></a></li>";
                        $li_update_ihs = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUpdateIhs('" . $this->enc($item_practitioner->id) . "')><em class='icon ni ni-send'></em><span>Update ID IHS</span></a></li>";
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

        // return $this->practitioner->getAll();
        return view('pages.practitioner.practitioner');
    }


    public function responseSS(Request $request, $id)
    {
        try {
            $endpoint = '/Practitioner?identifier=https://fhir.kemkes.go.id/id';
            $nik = $this->enc('nik|' . $this->dec($id));
            $response_satusehat  = $this->api_response_ss($endpoint, $nik);
            return view('pages.practitioner.practitioner-response-ss', [
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
            return view('pages.practitioner.practitioner-ubah', [
                "data_practitioner" => $this->practitioner_repo->getDataPractitionerFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function update(Request $request)
    {
        try {
            # update
            $this->practitioner_repo->updatePractitioner($request->all(), $this->dec($request->id_ubah));

            return redirect('praktisi')
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
            $color_ihs = 'danger';
            $nama_ihs = 'ID belum tersedia';

            $data_practitioner = $this->practitioner_repo->getDataPractitionerFind($this->dec($id));

            if (!empty($data_practitioner['nik'])) {
                $endpoint = '/Practitioner?identifier=https://fhir.kemkes.go.id/id';
                $nik = $this->enc('nik|' . $data_practitioner['nik']);
                $response_satusehat  = json_decode($this->api_response_ss($endpoint, $nik));


                if ($response_satusehat->total > 0) {
                    $id_ihs = $response_satusehat->entry[0]->resource->id;
                    $nama_ihs = $response_satusehat->entry[0]->resource->name[0]->text;
                    $color_ihs = 'success';
                }
            }

            return view('pages.practitioner.practitioner-ubah-ihs', [
                "data_practitioner" => $data_practitioner,
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

            $this->practitioner_repo->updateIhsPractitioner($param);

            return redirect('praktisi')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    # untuk Scheduler dan Manual
    public function runJob(Request $request, $param_id_jadwal)
    {
        try {
            # buat job log
            $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
            $param_start['start'] = $this->currentNow(); //dari APITrait
            $param_start['id'] = $param_id_jadwal; //config('constan.job_name.practitioner'); //id
            $param_start['status'] = 'Process'; //status awal process , lalu ada Completed

            # membuat Log status start job, job_report variable untuk mengambil last Id
            if ($this->practitioner_repo->getDataPractitionerReadyJob()->count() > 0) {
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                    return config('constan.error_message.toast_job_already');
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    # jalan kan job
                    PractitionerJob::dispatch(
                        $this->practitioner_repo,
                        $this->job_logs_repo,
                        $this->job_id
                    );
                    return config('constan.error_message.toast_job_running');
                }
            } else {
                return config('constan.error_message.toast_job_data_update');
            }
            # jalan kan job

        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function tambah()
    {
        return view('pages.practitioner.practitioner-tambah');
    }
    public function checkNik(Request $request)
    {
        try {
            $endpoint = '/Patient?identifier=https://fhir.kemkes.go.id/id';
            if ($request->pa == 'practitioner') {
                $endpoint = '/Practitioner?identifier=https://fhir.kemkes.go.id/id';
            }

            $nik = $this->enc('nik|' . $request->nik);

            $response_satusehat  = json_decode($this->api_response_ss($endpoint, $nik));

            if ($response_satusehat->total > 0) {
                $result['id_ihs'] = $response_satusehat->entry[0]->resource->id;
                $result['color'] = "bg-success text-white";
            } else {
                $result['id_ihs'] = config('constan.error_message.id_ihs_error');
                $result['color'] = "bg-danger text-white";
            }
            return $result;
        } catch (Throwable $e) {
            return "error";
        }
    }

    function storePractitioner(Request $request)
    {

        $request->validate([
            'nik' => 'required|unique:ss_practitioner,nik',
            'name' => 'required',
            'original_code' => 'required',
        ]);
        try {
            $this->practitioner_repo->storePractitioner($request->all());

            return redirect('praktisi-tambah')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }

        //simpan
    }
}

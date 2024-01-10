<?php

namespace App\Http\Controllers;

use App\Jobs\SinkronisasiJob;
use App\Models\Queue;
use App\Models\Sinkronisasi;
use App\Repositories\JobLogs\JobLogsInterface;
use App\Repositories\Sinkronisasi\SinkronisasiInterface;
use Illuminate\Http\Request;
use Throwable;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SinkronisasiController extends Controller
{
    use ApiTrait;
    use GeneralTrait;

    protected $sinkronisasi_repo;
    protected $dinamic_model;
    public $job_logs_repo;
    public $job_id;

    public function __construct(
        JobLogsInterface $jobLogsInterface,
        SinkronisasiInterface $sinkronisasiInterface
    ) {
        $this->sinkronisasi_repo = $sinkronisasiInterface;
        $this->job_logs_repo = $jobLogsInterface;
    }
    public function index()
    {
        return view("pages.sinkronisasi.sinkronisasi", [
            "data_sinkronisasi" => $this->sinkronisasi_repo->getDataSinkronisasi(),
        ]);
    }

    public function tambah()
    {
        return view('pages.sinkronisasi.sinkronisasi-tambah');
    }

    public function simpan(Request $request)
    {
        try {

            $this->sinkronisasi_repo->insertSinkronisasi($request->all());

            return redirect('sinkronisasi')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function ubah(Request $request, $id)
    {
        return view('pages.sinkronisasi.sinkronisasi-ubah', [
            "data_sinkronisasi" => $this->sinkronisasi_repo->getDataSinkronisasiById($this->dec($id)),
        ]);
    }

    public function update(Request $request)
    {
        try {
            // return $request->all();
            $this->sinkronisasi_repo->updateDataSinkronisasi($request->all(), $this->dec($request->id_ubah));
            return redirect('sinkronisasi')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }


    public function query(Request $request)
    {
        try {
            $odbc = $request->odbc;
            $text_query = $request->text_query;

            $result =  DB::connection($odbc)->select($text_query);
            // Print the keys
            return view('pages.sinkronisasi.sinkronisasi-table', [
                "data_query" => $result,

            ]);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }


    public function hapus(Request $request, $id)
    {
        return view('pages.sinkronisasi.sinkronisasi-hapus', [
            "data_sinkronisasi" => $this->sinkronisasi_repo->getDataSinkronisasiById($this->dec($id)),
        ]);
    }

    public function hapusData(Request $request)
    {
        try {
            $this->sinkronisasi_repo->deleteDataDinkronisasi($this->dec($request->id_hapus));
            return redirect('sinkronisasi')
                ->with("pesan", config('constan.message.form.success_delete'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    # sinkronisasi
    public function runJob(Request $request, $param_id_sinkronisasi)
    {
        try {
            // Sudah masing2 sinkronisasinya
            $id_sinkronisasi = $this->dec($param_id_sinkronisasi);

            $data_sinkronisasi = $this->sinkronisasi_repo->getDataSinkronisasiById($id_sinkronisasi);

            # membuat Log status start job, job_report variable untuk mengambil last Id
            # jika tidak ada data,tidak usah insert job log
            if (!empty($data_sinkronisasi)) {
                # Jalankan Job
                $param_start['action'] = config('constan.job_name.job_scheduler'); // manual atau schedule
                $param_start['start'] = $this->currentNow(); //dari APITrait
                $param_start['id'] = $data_sinkronisasi->kode; //id
                $param_start['status'] = 'Process'; //status awal process , lalu ada Completed
                # jika sudah ada data yang lagi antri gk ush dijlankan di job log
                if ($this->job_logs_repo->getDataJobLogAlreadyRun($param_start['id']) > 0) {
                    return config('constan.error_message.toast_job_already');
                } else {
                    $job_report = $this->job_logs_repo->insertJobLogsStart($param_start);
                    $this->job_id = $job_report->id;
                    SinkronisasiJob::dispatch(
                        $data_sinkronisasi, #data sinkronisasi ini sudah masing2
                        $this->job_logs_repo, #data job_logs
                        $this->job_id, #id untuk update status job logs
                    );
                    return config('constan.error_message.toast_job_running');
                }
            } else {
                return config('constan.error_message.toast_job_data_no');
            }
        } catch (Throwable $e) {
            // Log::info("Schedule Message : " . $e->getMessage());
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function tes()
    {
        $data_sinkronisasi = Sinkronisasi::where('status', 1)->get();
        return $data_sinkronisasi;
    }
}

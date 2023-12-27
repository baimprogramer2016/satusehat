<?php

namespace App\Jobs;

use App\Traits\ApiTrait;
use App\Traits\GeneralTrait;
use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PractitionerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GeneralTrait, ApiTrait;

    /**
     * Create a new job instance.
     */
    protected $practitioner_repo;
    protected $job_id;
    protected $job_logs_repo;
    public $timeout = 0; # artinya 120 detik / 2 menit

    public function __construct(
        $practitioner_repo,
        $job_logs_repo,
        $job_id #job_id untuk id unik job logs
    ) {
        $this->practitioner_repo = $practitioner_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # ambil data 100 Record sekali eksekusi
        $data_practitioner = $this->practitioner_repo->getDataPractitionerReadyJob();

        if ($data_practitioner->count() > 0) {

            # default isi
            $param['satusehat_id'] = null;
            $param['satusehat_process'] = 0;
            $param['satusehat_message'] =  config('constan.error_message.id_ihs_error');
            $param['satusehat_statuscode'] = 500;
            $param['satusehat_name'] = null;


            foreach ($data_practitioner as $item_practitioner) {

                $param['id'] = $item_practitioner->id;

                # jika nik tidak kosong lakukan tarik data dari API
                if (!empty($item_practitioner->nik)) {
                    # API get Data Practitioner
                    $endpoint = '/Practitioner?identifier=https://fhir.kemkes.go.id/id';
                    $nik = $this->enc('nik|' . $item_practitioner->nik);
                    $response_satusehat  = json_decode($this->api_response_ss($endpoint, $nik));

                    # jika ada ID IHS nya, replace defaultnya
                    if ($response_satusehat->total > 0) {
                        $param['satusehat_id'] = $response_satusehat->entry[0]->resource->id;
                        $param['satusehat_process'] = 1;
                        $param['satusehat_message'] = null;
                        $param['satusehat_statuscode'] = 200;
                        $param['satusehat_name'] = $response_satusehat->entry[0]->resource->name[0]->text;
                    }
                }
                # jika ada ID IHS update satusehat_id
                $this->practitioner_repo->updateIhsPractitioner($param);
            }

            # membuat Update status Completed end job pada Log
            $param_log['id'] = $this->job_id;
            $param_log['end'] =  $this->currentNow();
            $param_log['status'] =  'Completed';
            $param_log['error_message'] =  null;
            $this->job_logs_repo->updateJobLogsEnd($param_log);
        }
    }

    public function failed(Throwable $e)
    {
        // Called when the job is failing...
        $param_log['id'] = $this->job_id;
        $param_log['end'] =  $this->currentNow();
        $param_log['status'] =  'Failed';
        $param_log['error_message'] = $e->getMessage();
        $this->job_logs_repo->updateJobLogsEnd($param_log);
    }
}

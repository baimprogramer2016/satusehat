<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;
use Throwable;

class SpecimenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiTrait, JsonTrait;
    /**
     * Create a new job instance.
     */
    protected $specimen_repo;
    protected $parameter_repo;
    protected $job_id;
    protected $job_logs_repo;
    public $timeout = 0; # artinya 120 detik / 2 menit
    public function __construct(
        $parameter_repo,
        $job_logs_repo,
        $job_id, #job_id untuk id unik job logs
        $specimen_repo,
    ) {
        $this->parameter_repo = $parameter_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
        $this->specimen_repo = $specimen_repo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # ambil data 100 Record sekali eksekusi
        $data_data = $this->specimen_repo->getDataSpecimenReadyJob();

        if ($data_data->count() > 0) {
            # parameter body json
            # jika diperlukan parameter
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            #response default
            # INTI PERULANGAN PER REGNO
            foreach ($data_data as $item_data) {
                $satusehat_id = null;
                # response default
                $id = $item_data->id; # id

                # API POST Bundle
                $payload_specimen = $this->bodyManualSpecimen($item_data, $data_parameter);

                $response = $this->post_general_ss('/Specimen', $payload_specimen);
                $body_parse = json_decode($response->body());

                # hasil response diolah
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->specimen_repo->updateStatusSpecimen($id, $satusehat_id, $payload_specimen, $response);
                    }
                } else {
                    $this->specimen_repo->updateStatusSpecimen($id, $satusehat_id, $payload_specimen, $response);
                }
            }
            # membuat Update status Completed end job pada job Log

            $param_end['id'] = $this->job_id;
            $param_end['end'] =  $this->currentNow();
            $param_end['status'] =  'Completed';
            $param_end['error_message'] =  null;
            $this->job_logs_repo->updateJobLogsEnd($param_end);
        }
    }

    public function failed(Throwable $e)
    {
        // Called when the job is failing...
        $param_end['id'] = $this->job_id;
        $param_end['end'] =  $this->currentNow();
        $param_end['status'] =  'Failed';
        $param_end['error_message'] = $e->getMessage();
        $this->job_logs_repo->updateJobLogsEnd($param_end);
    }
}

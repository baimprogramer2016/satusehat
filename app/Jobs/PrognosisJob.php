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
use Illuminate\Support\Facades\Log;
use Throwable;

class PrognosisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiTrait, JsonTrait;
    /**
     * Create a new job instance.
     */
    protected $prognosis_repo, $condition_repo;
    protected $parameter_repo;
    protected $job_id;
    protected $job_logs_repo;
    public $timeout = 0; # artinya 120 detik / 2 menit
    public function __construct(
        $parameter_repo,
        $job_logs_repo,
        $job_id, #job_id untuk id unik job logs
        $prognosis_repo,
        $condition_repo,
    ) {
        $this->parameter_repo = $parameter_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
        $this->prognosis_repo = $prognosis_repo;
        $this->condition_repo = $condition_repo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # ambil data 100 Record sekali eksekusi
        $data_data = $this->prognosis_repo->getDataPrognosisReadyJob();

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

                $data_prognosis = $this->prognosis_repo->getDataPrognosisFind($item_data->id);

                // Log::info('Data Prognosis', ['prognosis' => $data_prognosis['encounter_originao']]);
                $data_condition = $this->condition_repo->getDataConditionByOriginalCode($data_prognosis['encounter_original_code']);
                // Log::info('Data Condition', ['Condition' => $data_condition]);

                # API POST Bundle
                $payload_prognosis = $this->bodyManualPrognosis($item_data, $data_parameter, $data_condition);

                $response = $this->post_general_ss('/ClinicalImpression', $payload_prognosis);
                $body_parse = json_decode($response->body());

                # hasil response diolah
                if ($response->successful()) {
                    # jika sukses tetapi hasil gagal
                    if ($body_parse->resourceType == 'OperationOutcome') {
                        $satusehat_id = null;
                    } else {
                        $satusehat_id = $body_parse->id;
                        # hanya jika sukses baru update status
                        $this->prognosis_repo->updateStatusPrognosis($id, $satusehat_id, $payload_prognosis, $response);
                    }
                } else {
                    $this->prognosis_repo->updateStatusPrognosis($id, $satusehat_id, $payload_prognosis, $response);
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

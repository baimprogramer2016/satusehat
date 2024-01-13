<?php

namespace App\Jobs;

use App\Models\Queue;
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


class BundleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiTrait, JsonTrait;

    /**
     * Create a new job instance.
     */
    protected $bundle_repo,
        $condition_repo,
        $observation_repo,
        $procedure_repo,
        $composition_repo;
    protected $parameter_repo;
    protected $job_id;
    protected $job_logs_repo;
    public $timeout = 0; # artinya 120 detik / 2 menit
    public function __construct(
        $bundle_repo,
        $condition_repo,
        $parameter_repo,
        $job_logs_repo,
        $job_id, #job_id untuk id unik job logs
        $observation_repo,
        $procedure_repo,
        $composition_repo
    ) {
        $this->bundle_repo = $bundle_repo; #data yang akan dieksekusi
        $this->condition_repo = $condition_repo;
        $this->parameter_repo = $parameter_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
        $this->observation_repo = $observation_repo;
        $this->procedure_repo = $procedure_repo;
        $this->composition_repo = $composition_repo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # ambil data 100 Record sekali eksekusi
        $data_bundle = $this->bundle_repo->getDataBundleReadyJob();

        if ($data_bundle->count() > 0) {
            # parameter body json
            $param_bundle['parameter'] = $this->parameter_repo->getDataParameterFirst();

            #response default
            # INTI PERULANGAN PER REGNO
            foreach ($data_bundle as $item_bundle) {
                $res['satusehat_id'] = null;
                $res['satusehat_send'] = 0;
                $res['satusehat_statuscode'] = 500;
                $res['satusehat_date'] = $this->currentNow();

                # response default
                $res['id'] = $item_bundle->id; # id encounter
                $res['encounter_original_code'] = $item_bundle->original_code; # regno encounter

                #parameter body json per item
                $param_bundle['bundle'] = $item_bundle;
                $param_bundle['observation'] = $this->observation_repo->getDataObservationByOriginalCode($item_bundle->original_code);
                $param_bundle['procedure'] = $this->procedure_repo->getDataProcedureByOriginalCode($item_bundle->original_code);
                $param_bundle['composition'] = $this->composition_repo->getDataCompositionByOriginalCode($item_bundle->original_code);

                # API POST Bundle
                $payload_bundle = $this->bodyBundle($param_bundle); // ada dua parameter

                $response = $this->post_general_ss('', $payload_bundle);
                $body_parse = json_decode($response->body());

                # response default - replace
                $res['satusehat_response'] = $response;
                $res['satusehat_request'] = $payload_bundle;

                # hasil response diolah
                if ($response->successful()) {
                    if (count($body_parse->entry) > 0) {

                        # response default
                        $res['satusehat_send'] = 1;
                        $res['satusehat_statuscode'] = 200;
                        $rank = 1;
                        foreach ($body_parse->entry as $item_response) {

                            # update status dari response masing2 punya response , encounter , condition dll
                            if ($item_response->response->resourceType == 'Encounter') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->bundle_repo->updateDataBundleEncounterJob($res);
                            }

                            # update status condition
                            if ($item_response->response->resourceType == 'Condition') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->condition_repo->updateDataBundleConditionJob($res, $rank);
                                $rank++;
                            }

                            # update status observation
                            if ($item_response->response->resourceType == 'Observation') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->observation_repo->updateDataBundleObservationJob($res);
                            }

                            # update status procedure
                            if ($item_response->response->resourceType == 'Procedure') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->procedure_repo->updateDataBundleProcedureJob($res);
                            }

                            # update status compositoin
                            if ($item_response->response->resourceType == 'Composition') {
                                # response default - replace
                                $res['satusehat_id'] = $item_response->response->resourceID;

                                $this->composition_repo->updateDataBundleCompositionJob($res);
                            }
                            # update status dari response
                        }
                    }
                } else {
                    $this->bundle_repo->updateDataBundleEncounterJob($res);
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

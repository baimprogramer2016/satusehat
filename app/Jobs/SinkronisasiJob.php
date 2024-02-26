<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\ApiTrait;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SinkronisasiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GeneralTrait, ApiTrait;

    /**
     * Create a new job instance.
     */
    public $sinkronisasi_repo, $job_logs_repo, $job_id;
    public $timeout = 0; #
    public function __construct(
        $sinkronisasi_repo,
        $job_logs_repo,
        $job_id,
    ) {
        $this->sinkronisasi_repo = $sinkronisasi_repo;
        $this->job_logs_repo = $job_logs_repo;
        $this->job_id = $job_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        # cek lagi jika ada
        if (!empty($this->sinkronisasi_repo)) {

            # proses migrasi
            # source data
            $odbc = $this->sinkronisasi_repo->odbc;
            $text_query = $this->sinkronisasi_repo->query;
            $sp = $this->sinkronisasi_repo->sp;
            $truncate_table = $this->sinkronisasi_repo->tr_table;

            $result_query =  DB::connection($odbc)->select($text_query);

            # jika ada truncate dikosong kan dahulu
            if ($truncate_table == 1) {
                DB::table($this->sinkronisasi_repo->target)->truncate();
            }

            # insert target
            foreach ($result_query as $item_result) {
                $object_properties = get_object_vars($item_result);

                // Iterasi melalui setiap properti dan mengganti tanda petik dalam nilai properti
                foreach ($object_properties as $property => $value) {
                    // Ganti tanda petik dengan string kosong
                    $object_properties[$property] = preg_replace('/[^A-Za-z0-9\-]/', '', $value);
                }
                DB::table($this->sinkronisasi_repo->target)->insert((array) $object_properties);
            }

            # jalan kan SP jika ada dan setelah query di jalankan
            if (!empty($sp)) {
                # jika ada sp lebih dari 1
                if (strpos($sp, "#")) {
                    $sp_result = explode("#", $sp);
                    foreach ($sp_result as $index => $item_result) {
                        echo $item_result[$index];
                        DB::select($item_result[$index]);
                    }
                } else {

                    DB::select($sp);
                }
            }
        }
        # membuat Update status Completed end job pada job Log
        $param_end['id'] = $this->job_id;
        $param_end['end'] =  $this->currentNow();
        $param_end['status'] =  'Completed';
        $param_end['error_message'] =  null;
        $this->job_logs_repo->updateJobLogsEnd($param_end);
    }
    public function failed(Throwable $e)
    {
        Log::info($e);
        // Called when the job is failing...
        $param_end['id'] = $this->job_id;
        $param_end['end'] =  $this->currentNow();
        $param_end['status'] =  'Failed';
        $param_end['error_message'] = $e->getMessage();
        $this->job_logs_repo->updateJobLogsEnd($param_end);
    }
}

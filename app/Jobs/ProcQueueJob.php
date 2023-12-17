<?php

namespace App\Jobs;

use App\Models\Queue as KueTable;
use App\Models\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $timeout = 120; # artinya 120 detik / 2 menit
    protected $angka;
    public function __construct($angka)
    {
        $this->angka = $angka;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        for ($i = 1; $i <= 10000; $i++) {
            Queue::insert([
                'angka' => $this->angka
            ]);
        }
    }
}

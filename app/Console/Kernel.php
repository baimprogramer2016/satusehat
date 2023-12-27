<?php

namespace App\Console;


use App\Models\Jadwal;
use App\Models\Sinkronisasi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Crypt;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->job(new PatienJob(8))->everyMinute();
        // $schedule->command('patient:cron')->cron('* * * * *');;
        // $schedule->call('App\Http\Controllers\MyController@MyAction')->everyMinute();

        // menjalan scheduler Jadwal
        $data_jadwal = Jadwal::where('status', 1)->where('command', '!=', '')->get();
        foreach ($data_jadwal as $item_jadwal_kernel) {
            $schedule->call($item_jadwal_kernel->command)->cron($item_jadwal_kernel->cron);
        }
        // menjalan scheduler Sinkronisasi
        $data_sinkronisasi = Sinkronisasi::where('status', 1)->get();
        foreach ($data_sinkronisasi as $item_sinkronisasi_kernel) {
            $schedule->call('App\Http\Controllers\SinkronisasiController@runJob', ["param_id_sinkronisasi" => Crypt::encrypt($item_sinkronisasi_kernel->id)])->cron($item_sinkronisasi_kernel->cron);
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

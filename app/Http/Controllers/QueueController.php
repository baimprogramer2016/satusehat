<?php

namespace App\Http\Controllers;

use App\Jobs\ProcQueueJob;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\FormattableHandlerInterface;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        // dispatch(new ProcQueueJob(6));
        ProcQueueJob::dispatch(7);
        return 'Berhasil';
    }

    public function query(Request $request)
    {
        $result =   DB::connection('odbc_mssql')->select("select TOP 1000 segment from sales");


        // Print the keys
        return view('pages.sinkronisasi.tes', [
            "data_query" => $result
        ]);
    }
}

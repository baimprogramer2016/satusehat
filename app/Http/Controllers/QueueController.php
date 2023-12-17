<?php

namespace App\Http\Controllers;

use App\Jobs\ProcQueueJob;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        // dispatch(new ProcQueueJob(6));
        ProcQueueJob::dispatch(7);
        return 'Berhasil';
    }
}

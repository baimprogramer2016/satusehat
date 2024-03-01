<?php

namespace App\Http\Controllers;

use App\Repositories\LogError\LogErrorInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\Datatables;
use Throwable;

class LogErrorController extends Controller
{

    private $log_error_repo;
    public function __construct(
        LogErrorInterface $logErrorInterface,
    ) {
        $this->log_error_repo = $logErrorInterface;
    } //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->log_error_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        // return $this->patient->getAll();
        return view('pages.log-error.log-error');
    }
}

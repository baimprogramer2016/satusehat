<?php

namespace App\Http\Controllers;

use App\Repositories\PrognosisCode\PrognosisCodeInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\Datatables;
use Throwable;

class PrognosisCodeController extends Controller
{

    use GeneralTrait;
    use ApiTrait;

    public $prognosis_code_repo;

    public function __construct(
        PrognosisCodeInterface $prognosisCodeInterface
    ) {
        $this->prognosis_code_repo = $prognosisCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->prognosis_code_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('daftar_prognosis_master', function ($item_prognosis_master) {
                //     // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                //     $daftar_prognosis_master = "<div class='input-group' >
                //     <input type='text' href='#file-upload' readonly name='id_medication_$item_prognosis_master->id' data-toggle='modal' class='form-control' value='" . $item_medication->kfa_code . "' onClick=modalViewKfa(event) placeholder='Klik Pilih KFA' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                // </div>";
                //     return $daftar_prognosis_master;
                // })
                // ->rawColumns(['daftar_prognosis_master'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.prognosis-code.prognosis-code');
    }
}

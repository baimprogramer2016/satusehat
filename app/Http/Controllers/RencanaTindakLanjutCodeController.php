<?php

namespace App\Http\Controllers;

use App\Repositories\RencanaTindakLanjutCode\RencanaTindakLanjutCodeInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class RencanaTindakLanjutCodeController extends Controller
{

    use GeneralTrait;
    use ApiTrait;

    public $rencana_tindak_lanjut_code_repo;

    public function __construct(
        RencanaTindakLanjutCodeInterface $rencanaTindakLanjutCodeInterface
    ) {
        $this->rencana_tindak_lanjut_code_repo = $rencanaTindakLanjutCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->rencana_tindak_lanjut_code_repo->getQuery();
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
        return view('pages.rencana-tindak-lanjut-code.rencana-tindak-lanjut-code');
    }
}

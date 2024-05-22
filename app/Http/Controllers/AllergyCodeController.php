<?php

namespace App\Http\Controllers;

use App\Repositories\AllergyCode\AllergyCodeInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\Datatables;
use Throwable;

class AllergyCodeController extends Controller
{

    use GeneralTrait;
    use ApiTrait;

    public $allergy_code_repo;

    public function __construct(
        AllergyCodeInterface $allergyCodeInterface
    ) {
        $this->allergy_code_repo = $allergyCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->allergy_code_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('daftar_allergy_master', function ($item_allergy_master) {
                //     // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                //     $daftar_allergy_master = "<div class='input-group' >
                //     <input type='text' href='#file-upload' readonly name='id_medication_$item_allergy_master->id' data-toggle='modal' class='form-control' value='" . $item_medication->kfa_code . "' onClick=modalViewKfa(event) placeholder='Klik Pilih KFA' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                // </div>";
                //     return $daftar_allergy_master;
                // })
                // ->rawColumns(['daftar_allergy_master'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.allergy-code.allergy-code');
    }
}

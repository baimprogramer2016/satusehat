<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRequest\CategoryRequestInterface;
use App\Repositories\Loinc\LoincInterface;
use App\Repositories\Snomed\SnomedInterface;
use App\Repositories\MasterProcedure\MasterProcedureInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class MasterProcedureController extends Controller
{
    public $master_procedure_repo;
    public $snomed_repo;
    public $loinc_repo;
    public $category_request_repo;

    public $id_master_procedure;
    public function __construct(
        MasterProcedureInterface $masterProcedureInterface,
        SnomedInterface $snomedInterface,
        LoincInterface $loincInterface,
        CategoryRequestInterface $categoryReqeustInterface
    ) {
        $this->master_procedure_repo = $masterProcedureInterface;
        $this->snomed_repo = $snomedInterface;
        $this->loinc_repo = $loincInterface;
        $this->category_request_repo = $categoryReqeustInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->master_procedure_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('daftar_snomed', function ($item_master_procedure) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_snomed = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_master_procedure_$item_master_procedure->id' data-toggle='modal' class='form-control' value='" . $item_master_procedure->snomed_code . "' onClick=modalViewSnomed(event) placeholder='Klik Pilih Snomed' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_snomed;
                })
                ->addColumn('daftar_loinc', function ($item_master_procedure) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_snomed = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_master_procedure_loinc_$item_master_procedure->id' data-toggle='modal' class='form-control' value='" . $item_master_procedure->loinc_code . "' onClick=modalViewLoinc(event) placeholder='Klik Pilih Loinc' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_snomed;
                })
                ->addColumn('daftar_category', function ($item_master_procedure) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_category = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_master_procedure_category_$item_master_procedure->id' data-toggle='modal' class='form-control' value='" . $item_master_procedure->category_request . "' onClick=modalViewCategory(event) placeholder='Klik Pilih Category' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_category;
                })
                ->rawColumns(['daftar_loinc', 'daftar_snomed', 'daftar_category'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.master-procedure.master-procedure');
    }

    public function modalSnomed(Request $request, $id)
    {
        $this->id_master_procedure = $id;
        try {
            $data_master_procedure = $this->master_procedure_repo->getMasterProcedureId($this->id_master_procedure);
            return view('pages.master-procedure.master-procedure-snomed', [
                "data_master_procedure" => $data_master_procedure,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function modalLoinc(Request $request, $id)
    {
        $this->id_master_procedure = $id;
        try {
            $data_master_procedure = $this->master_procedure_repo->getMasterProcedureId($this->id_master_procedure);
            return view('pages.master-procedure.master-procedure-loinc', [
                "data_master_procedure" => $data_master_procedure,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function modalCategory(Request $request, $id)
    {
        $this->id_master_procedure = $id;
        try {
            $data_master_procedure = $this->master_procedure_repo->getMasterProcedureId($this->id_master_procedure);
            return view('pages.master-procedure.master-procedure-category', [
                "data_master_procedure" => $data_master_procedure,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function getDataSnomed(Request $request, $id_master_procedure)
    {
        $this->id_master_procedure = $id_master_procedure;
        try {
            if ($request->ajax()) {
                $data = $this->snomed_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pilih_snomed', function ($item_snomed) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_snomed = "<button data-dismiss='modal' onClick=updateSnomed('" . $this->id_master_procedure . "','" . $item_snomed->snomed_code . "') class='btn btn-warning'>Pilih</button>";
                        return $pilih_snomed;
                    })
                    ->rawColumns(['pilih_snomed'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function getDataLoinc(Request $request, $id_master_procedure)
    {
        $this->id_master_procedure = $id_master_procedure;
        try {
            if ($request->ajax()) {
                $data = $this->loinc_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pilih_loinc', function ($item_loinc) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_loinc = "<button data-dismiss='modal' onClick=updateLoinc('" . $this->id_master_procedure . "','" . $item_loinc->loinc_code . "') class='btn btn-warning'>Pilih</button>";
                        return $pilih_loinc;
                    })
                    ->rawColumns(['pilih_loinc'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e;
        }
    }


    public function getDataCategory(Request $request, $id_master_procedure)
    {
        $this->id_master_procedure = $id_master_procedure;
        try {
            if ($request->ajax()) {
                $data = $this->category_request_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pilih_category', function ($item_category) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_category = "<button data-dismiss='modal' onClick=updateCategory('" . $this->id_master_procedure . "','" . str_replace(' ', '|', $item_category->display) . "') class='btn btn-warning'>Pilih</button>";
                        return $pilih_category;
                    })
                    ->rawColumns(['pilih_category'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function updateSnomed(Request $request)
    {
        return $this->master_procedure_repo->updateMasterProcedureSnomed($request->all());
    }
    public function updateLoinc(Request $request)
    {
        return $this->master_procedure_repo->updateMasterProcedureLoinc($request->all());
    }
    public function updateCategory(Request $request)
    {
        return $this->master_procedure_repo->updateMasterProcedureCategory($request->all());
    }
}

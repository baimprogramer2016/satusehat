<?php

namespace App\Http\Controllers;

use App\Repositories\AllergyCode\AllergyCodeInterface;
use App\Repositories\AllergyMaster\AllergyMasterInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\Datatables;
use Throwable;

class AllergyMasterController extends Controller
{
    use GeneralTrait;
    use ApiTrait;

    public $allergy_master_repo;
    public $allergy_code_repo;

    public $id_allergy_master;

    public function __construct(
        AllergyMasterInterface $allergyMasterInterface,
        AllergyCodeInterface $allergyCodeInterface
    ) {
        $this->allergy_master_repo = $allergyMasterInterface;
        $this->allergy_code_repo = $allergyCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->allergy_master_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('daftar_kode', function ($item_allergy_master) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_kode = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_allergy_master_$item_allergy_master->id' data-toggle='modal' class='form-control' value='" . $item_allergy_master->code_satusehat . "' onClick=modalViewKode(event) placeholder='Klik Kode' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_kode;
                })
                ->rawColumns(['daftar_kode'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.allergy-master.allergy-master');
    }
    public function modalKode(Request $request, $id)
    {

        $this->id_allergy_master = $id;

        try {
            $data_allergy_master = $this->allergy_master_repo->getAllergyMasterId($this->id_allergy_master);

            return view('pages.allergy-master.allergy-master-ubah', [
                "data_allergy_master" => $data_allergy_master,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function getDataKode(Request $request, $id_allergy_master)
    {
        $this->id_allergy_master = $id_allergy_master;
        try {
            if ($request->ajax()) {
                $data = $this->allergy_code_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('kolom_numerator', function ($item_kfa) {
                    //     $nominator =  $item_kfa->numerator . $item_kfa->numerator_satuan .  ' / ' . $item_kfa->denominator . $item_kfa->denominator_satuan;
                    //     $kolom_numerator = '<td>' . $nominator . '</td>';
                    //     return $kolom_numerator;
                    // })
                    ->addColumn('pilih_kode', function ($item_kode) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_kode = "<button data-dismiss='modal' onClick=updateKode('" . $this->id_allergy_master . "','" . $item_kode->code . "') class='btn btn-warning'>Pilih</button>";
                        return $pilih_kode;
                    })
                    ->rawColumns(['pilih_kode'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function updateKode(Request $request)
    {
        // return $request->all();
        // return $request;
        return $this->allergy_master_repo->updateAllergyMasterCode($request->all());
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\PrognosisCode\PrognosisCodeInterface;
use App\Repositories\PrognosisMaster\PrognosisMasterInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class PrognosisMasterController extends Controller
{
    use GeneralTrait;
    use ApiTrait;

    public $prognosis_master_repo;
    public $prognosis_code_repo;

    public $id_prognosis_master;

    public function __construct(
        PrognosisMasterInterface $PrognosisMasterInterface,
        PrognosisCodeInterface $PrognosisCodeInterface
    ) {
        $this->prognosis_master_repo = $PrognosisMasterInterface;
        $this->prognosis_code_repo = $PrognosisCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->prognosis_master_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('daftar_kode', function ($item_prognosis_master) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_kode = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_prognosis_master_$item_prognosis_master->id' data-toggle='modal' class='form-control' value='" . $item_prognosis_master->code_satusehat . "' onClick=modalViewKode(event) placeholder='Klik Kode' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_kode;
                })
                ->rawColumns(['daftar_kode'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.prognosis-master.prognosis-master');
    }
    public function modalKode(Request $request, $id)
    {

        $this->id_prognosis_master = $id;

        try {
            $data_prognosis_master = $this->prognosis_master_repo->getPrognosisMasterId($this->id_prognosis_master);

            return view('pages.prognosis-master.prognosis-master-ubah', [
                "data_prognosis_master" => $data_prognosis_master,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function getDataKode(Request $request, $id_prognosis_master)
    {
        $this->id_prognosis_master = $id_prognosis_master;
        try {
            if ($request->ajax()) {
                $data = $this->prognosis_code_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('kolom_numerator', function ($item_kfa) {
                    //     $nominator =  $item_kfa->numerator . $item_kfa->numerator_satuan .  ' / ' . $item_kfa->denominator . $item_kfa->denominator_satuan;
                    //     $kolom_numerator = '<td>' . $nominator . '</td>';
                    //     return $kolom_numerator;
                    // })
                    ->addColumn('pilih_kode', function ($item_kode) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_kode = "<button data-dismiss='modal' onClick=updateKode('" . $this->id_prognosis_master . "','" . $item_kode->code . "') class='btn btn-warning'>Pilih</button>";
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
        return $this->prognosis_master_repo->updatePrognosisMasterCode($request->all());
    }
}

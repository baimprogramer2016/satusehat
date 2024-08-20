<?php

namespace App\Http\Controllers;

use App\Repositories\RencanaTindakLanjutCode\RencanaTindakLanjutCodeInterface;
use App\Repositories\RencanaTindakLanjutMaster\RencanaTindakLanjutMasterInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class RencanaTindakLanjutMasterController extends Controller
{
    use GeneralTrait;
    use ApiTrait;

    public $rencana_tindak_lanjut_master_repo;
    public $rencana_tindak_lanjut_code_repo;

    public $id_rencana_tindak_lanjut_master;

    public function __construct(
        RencanaTindakLanjutMasterInterface $rencanaTindakLanjutMasterInterface,
        RencanaTindakLanjutCodeInterface $rencanaTindakLanjutCodeInterface
    ) {
        $this->rencana_tindak_lanjut_master_repo = $rencanaTindakLanjutMasterInterface;
        $this->rencana_tindak_lanjut_code_repo = $rencanaTindakLanjutCodeInterface;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->rencana_tindak_lanjut_master_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('daftar_kode', function ($item_rencana_tindak_lanjut_master) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_kode = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_rencana_tindak_lanjut_master_$item_rencana_tindak_lanjut_master->id' data-toggle='modal' class='form-control' value='" . $item_rencana_tindak_lanjut_master->code_satusehat . "' onClick=modalViewKode(event) placeholder='Klik Kode' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_kode;
                })
                ->rawColumns(['daftar_kode'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.rencana-tindak-lanjut-master.rencana-tindak-lanjut-master');
    }
    public function modalKode(Request $request, $id)
    {

        $this->id_rencana_tindak_lanjut_master = $id;

        try {
            $data_rencana_tindak_lanjut_master = $this->rencana_tindak_lanjut_master_repo->getRencanaTindakLanjutMasterId($this->id_rencana_tindak_lanjut_master);

            return view('pages.rencana-tindak-lanjut-master.rencana-tindak-lanjut-master-ubah', [
                "data_rencana_tindak_lanjut_master" => $data_rencana_tindak_lanjut_master,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function getDataKode(Request $request, $id_rencana_tindak_lanjut_master)
    {
        $this->id_rencana_tindak_lanjut_master = $id_rencana_tindak_lanjut_master;
        try {
            if ($request->ajax()) {
                $data = $this->rencana_tindak_lanjut_code_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('kolom_numerator', function ($item_kfa) {
                    //     $nominator =  $item_kfa->numerator . $item_kfa->numerator_satuan .  ' / ' . $item_kfa->denominator . $item_kfa->denominator_satuan;
                    //     $kolom_numerator = '<td>' . $nominator . '</td>';
                    //     return $kolom_numerator;
                    // })
                    ->addColumn('pilih_kode', function ($item_kode) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_kode = "<button data-dismiss='modal' onClick=updateKode('" . $this->id_rencana_tindak_lanjut_master . "','" . $item_kode->code . "') class='btn btn-warning'>Pilih</button>";
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
        return $this->rencana_tindak_lanjut_master_repo->updateRencanaTindakLanjutMasterCode($request->all());
    }
}

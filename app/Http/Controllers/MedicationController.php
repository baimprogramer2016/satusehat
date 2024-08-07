<?php

namespace App\Http\Controllers;

use App\Repositories\Kfa\KfaInterface;
use App\Repositories\Medication\MedicationInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class MedicationController extends Controller
{
    use GeneralTrait;
    use ApiTrait;

    public $medication_repo;
    public $kfa_repo;

    public $id_medication;
    public function __construct(
        MedicationInterface $medicationInterface,
        KfaInterface $kfaInterface
    ) {
        $this->medication_repo = $medicationInterface;
        $this->kfa_repo = $kfaInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->medication_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('daftar_kfa', function ($item_medication) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    $daftar_kfa = "<div class='input-group' >
                    <input type='text' href='#file-upload' readonly name='id_medication_$item_medication->id' data-toggle='modal' class='form-control' value='" . $item_medication->kfa_code . "' onClick=modalViewKfa(event) placeholder='Klik Pilih KFA' aria-label='Input group example' aria-describedby='btnGroupAddon'>
                </div>";
                    return $daftar_kfa;
                })
                ->rawColumns(['daftar_kfa'])
                ->make(true);
        }
        // return $this->patient->getAll();
        return view('pages.medication.medication');
    }

    public function modalKfa(Request $request, $id)
    {
        $this->id_medication = $id;
        try {
            $data_medication = $this->medication_repo->getMedicationId($this->id_medication);
            return view('pages.medication.medication-ubah', [
                "data_medication" => $data_medication,
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function getDataKFa(Request $request, $id_medication)
    {
        $this->id_medication = $id_medication;
        try {
            if ($request->ajax()) {
                $data = $this->kfa_repo->getQuery();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('kolom_numerator', function ($item_kfa) {
                        $nominator =  $item_kfa->numerator . $item_kfa->numerator_satuan .  ' / ' . $item_kfa->denominator . $item_kfa->denominator_satuan;
                        $kolom_numerator = '<td>' . $nominator . '</td>';
                        return $kolom_numerator;
                    })
                    ->addColumn('pilih_kfa', function ($item_kfa) {
                        // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        $pilih_kfa = "<button data-dismiss='modal' onClick=updateKfa('" . $this->id_medication . "','" . $item_kfa->kode_kfa . "') class='btn btn-warning'>Pilih</button>";
                        return $pilih_kfa;
                    })
                    ->rawColumns(['kolom_numerator', 'pilih_kfa'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e;
        }
    }

    public function updateKfa(Request $request)
    {
        return $this->medication_repo->updateMedicationKfa($request->all());
    }
}

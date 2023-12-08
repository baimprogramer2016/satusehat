<?php

namespace App\Http\Controllers;

use App\Repositories\Kfa\KfaInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use Throwable;


class KfaController extends Controller
{
    use GeneralTrait;
    private $kfa_repo;

    public function __construct(KfaInterface $kfaRepository)
    {
        $this->kfa_repo = $kfaRepository;
    } //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->kfa_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pv', function ($item_patient) {

                    $pv = $item_patient->kode_pv . ' - ' . $item_patient->nama_pv;
                    return $pv;
                })
                ->addColumn('pa', function ($item_patient) {

                    $pv = $item_patient->kode_pa . ' - ' . $item_patient->nama_pa;
                    return $pv;
                })
                ->addColumn('kekuatan', function ($item_patient) {

                    $pv = $item_patient->numerator . ' ' . $item_patient->numerator_satuan . ' / ' . $item_patient->denominator . ' ' . $item_patient->denominator_satuan;
                    return $pv;
                })
                ->rawColumns(['pv', 'pa', 'kekuatan'])
                ->make(true);
        }

        // return $this->patient->getAll();
        return view('pages.kfa.kfa');
    }
}

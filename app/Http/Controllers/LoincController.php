<?php

namespace App\Http\Controllers;

use App\Repositories\Loinc\LoincInterface;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;

use Throwable;

class LoincController extends Controller
{
    use GeneralTrait;
    protected $loinc_repo;

    public function __construct(
        LoincInterface $loincInterface
    ) {
        $this->loinc_repo = $loincInterface;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->loinc_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($item_loinc) {
                    // $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">' . $item_patient . '</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';

                    $li_update = "<li><a href='#file-upload' data-toggle='modal' onClick=modalUbah('" . $this->enc($item_loinc->id) . "')><em class='icon ni ni-edit'></em><span>Ubah</span></a></li>";
                    $li_delete = "<li><a href='#file-upload' data-toggle='modal' onClick=modalHapus('" . $this->enc($item_loinc->id) . "')><em class='icon ni ni-trash'></em><span>Hapus</span></a></li>";


                    $action_update = ' <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            ' .
                        $li_update .
                        $li_delete . '
                            </ul>
                        </div>';

                    return $action_update;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view("pages.loinc.loinc");
    }
    public function tambah()
    {
        return view("pages.loinc.loinc-tambah");
    }
    public function simpan(Request $request)
    {
        try {

            $this->loinc_repo->storeLoinc($request->all());

            return redirect('loinc')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function hapus(Request $request, $id)
    {
        return view('pages.loinc.loinc-hapus', [
            "data_loinc" => $this->loinc_repo->getDataLoincFind($this->dec($id)),
        ]);
    }
    public function ubah(Request $request, $id)
    {
        return view('pages.loinc.loinc-ubah', [
            "data_loinc" => $this->loinc_repo->getDataLoincFind($this->dec($id)),
        ]);
    }

    public function update(Request $request)
    {
        try {
            $this->loinc_repo->updateLoinc($request, $this->dec($request->id_ubah));
            return redirect('loinc')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function hapusData(Request $request)
    {
        try {

            $data =  $this->loinc_repo->deleteLoinc($this->dec($request->id_hapus));
            $data->delete();

            return redirect('loinc')
                ->with("pesan", config('constan.message.form.success_delete'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

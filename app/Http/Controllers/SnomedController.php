<?php

namespace App\Http\Controllers;

use App\Repositories\Snomed\SnomedInterface;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Throwable;

class SnomedController extends Controller
{
    use GeneralTrait;
    protected $snomed_repo;

    public function __construct(
        SnomedInterface $snomedInterface,
    ) {
        $this->snomed_repo = $snomedInterface;
    }

    public function index()
    {
        return view("pages.snomed.snomed", [
            "data_snomed" => $this->snomed_repo->getDataSnomed(),
        ]);
    }
    public function tambah()
    {
        return view("pages.snomed.snomed-tambah", [
            "data_desc_resource" => $this->snomed_repo->getDescResource()
        ]);
    }
    public function simpan(Request $request)
    {
        try {

            $this->snomed_repo->storeSnomed($request->all());

            return redirect('snomed')
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
        return view('pages.snomed.snomed-hapus', [
            "data_snomed" => $this->snomed_repo->getDataSnomedFind($this->dec($id)),
        ]);
    }
    public function ubah(Request $request, $id)
    {
        return view('pages.snomed.snomed-ubah', [
            "data_snomed" => $this->snomed_repo->getDataSnomedFind($this->dec($id)),
            "data_desc_resource" => $this->snomed_repo->getDescResource()
        ]);
    }

    public function update(Request $request)
    {
        try {
            $this->snomed_repo->updateSnomed($request, $this->dec($request->id_ubah));
            return redirect('snomed')
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

            $data =  $this->snomed_repo->deleteSnomed($this->dec($request->id_hapus));
            $data->delete();

            return redirect('snomed')
                ->with("pesan", config('constan.message.form.success_delete'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

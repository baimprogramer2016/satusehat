<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Status;
use App\Repositories\Organization\OrganizationInterface;
use App\Repositories\Status\StatusInterface;
use Illuminate\Http\Request;
use Throwable;
use App\Traits\GeneralTrait;

class OraganizationController extends Controller
{

    use GeneralTrait;

    protected $organization_repo;
    protected $status_repo;

    public function __construct(OrganizationInterface $organizationRepository, StatusInterface $statusRepository)
    {
        $this->organization_repo = $organizationRepository;
        $this->status_repo = $statusRepository;
    }
    public function index()
    {
        return view("pages.organization.organization", [
            "data_organisasi" => $this->organization_repo->getDataOrganization(),
        ]);
    }
    public function tambah()
    {
        return view("pages.organization.organization-tambah", [
            "data_bagian" => $this->organization_repo->getDataOrganizationIdSuccess(),
            "data_status" => $this->status_repo->getDataStatusNotSend()
        ]);
    }
    public function simpan(Request $request)
    {
        try {

            $this->organization_repo->storeOrganization($request->all());

            return redirect('organisasi')
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
        return view('pages.organization.organization-hapus', [
            "data_organization" => $this->organization_repo->getDataOrganizationFind($this->dec($id)),
        ]);
    }

    public function hapusData(Request $request)
    {
        try {

            $data =  $this->organization_repo->deleteOrganization($this->dec($request->id_hapus));
            $data->delete();

            return redirect('organisasi')
                ->with("pesan", config('constan.message.form.success_delete'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function struktur()
    {
    }
    public function ubah(Request $request, $id)
    {
        return view('pages.organization.organization-ubah', [
            "data_bagian" => $this->organization_repo->getDataOrganizationIdSuccess(),
            "data_organization" => $this->organization_repo->getDataOrganizationFind($this->dec($id)),
            "data_status" => $this->status_repo->getDataStatusNotSend()
        ]);
    }
    public function update(Request $request)
    {
        try {
            $this->organization_repo->updateOrganization($request, $this->dec($request->id_ubah));
            return redirect('organisasi')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function responseSS(Request $request, $id)
    {
        try {
            $response_satusehat  = $this->api_response_ss('Organization', $id);
            return view('pages.organization.organization-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\Organization\OrganizationInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\Status\StatusInterface;
use Illuminate\Http\Request;
use Throwable;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;

class OrganizationController extends Controller
{

    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    protected $organization_repo;
    protected $status_repo;
    protected $parameter_repo;

    public function __construct(
        ParameterInterface $parameterInterface,
        OrganizationInterface $organizationRepository,
        StatusInterface $statusRepository
    ) {
        $this->organization_repo = $organizationRepository;
        $this->status_repo = $statusRepository;
        $this->parameter_repo = $parameterInterface;
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
            "seq_no" => $this->autoSeq('ORG'),
            "data_parameter" => $this->parameter_repo->getDataParameterFirst(),
            "data_bagian" => $this->organization_repo->getDataOrganizationIdSuccess(),
            "data_status" => $this->status_repo->getDataStatusNotSend()
        ]);
    }
    public function struktur()
    {
        return view("pages.organization.organization-struktur");
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
    public function ubah(Request $request, $id)
    {
        return view('pages.organization.organization-ubah', [
            "data_parameter" => $this->parameter_repo->getDataParameterFirst(),
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
            $response_satusehat  = $this->api_response_ss('/Organization', $id);
            return view('pages.organization.organization-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
    public function modalKirimSS(Request $request, $id)
    {
        try {
            return view('pages.organization.organization-kirim-ss', [
                "data_organization" => $this->organization_repo->getDataOrganizationFind($this->dec($id)),
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function kirimSS(Request $request)
    {
        try {
            $data_organization = $this->organization_repo->getDataOrganizationFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            $payload_organization = $this->bodyOrganization($data_organization, $data_parameter);
            $response = $this->post_general_ss('/Organization', $payload_organization);
            $body_parse = json_decode($response->body());

            $satusehat_id = null;
            if ($response->successful()) {
                $satusehat_id = $body_parse->id;
            }
            # update status ke database
            $this->organization_repo->updateStatusOrganization($this->dec($request->id), $satusehat_id, $payload_organization, $response);
            return $response;
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

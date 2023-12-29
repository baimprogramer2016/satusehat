<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Organization\OrganizationInterface;
use App\Repositories\Parameter\ParameterInterface;
use App\Repositories\PhysicalType\PhysicalTypeInterface;
use App\Repositories\Status\StatusInterface;
use App\Traits\JsonTrait;
use Throwable;


class LocationController extends Controller
{

    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    protected $location_repo;
    protected $organization_repo;
    protected $status_repo;
    protected $physical_type_repo;
    protected $parameter_repo;

    public function __construct(
        LocationInterface $locationRepository,
        OrganizationInterface $organizationRepository,
        StatusInterface $statusRepository,
        PhysicalTypeInterface $physicalTypeRepository,
        ParameterInterface $parameterInterface
    ) {
        $this->location_repo = $locationRepository;
        $this->organization_repo = $organizationRepository;
        $this->status_repo = $statusRepository;
        $this->physical_type_repo = $physicalTypeRepository;
        $this->parameter_repo = $parameterInterface;
    }
    public function index()
    {
        return view("pages.location.location", [
            "data_lokasi" => $this->location_repo->getDataLocation(),
        ]);
    }

    public function tambah()
    {
        return view("pages.location.location-tambah", [
            "data_bagian" => $this->organization_repo->getDataOrganizationIdSuccess(),
            "data_status" => $this->status_repo->getDataStatusNotSend(),
            "data_type" => $this->physical_type_repo->getDataPhysicalType(),
            "data_poli" => $this->location_repo->getDataPoli()
        ]);
    }
    public function simpan(Request $request)
    {
        try {
            # mendapatkan display physical type
            $physical_type_display = $this->physical_type_repo->getDataPhysicalTypeCode($request->physical_type_code);
            # simpan
            $this->location_repo->storeLocation($request->all(), $physical_type_display);

            return redirect('lokasi')
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
        return view('pages.location.location-hapus', [
            "data_location" => $this->location_repo->getDataLocationFind($this->dec($id)),
        ]);
    }

    public function hapusData(Request $request)
    {
        try {

            $this->location_repo->deleteLocation($this->dec($request->id_hapus));

            return redirect('lokasi')
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
        try {
            return view('pages.location.location-ubah', [
                "data_location" => $this->location_repo->getDataLocationFind($this->dec($id)),
                "data_bagian" => $this->organization_repo->getDataOrganizationIdSuccess(),
                "data_status" => $this->status_repo->getDataStatusNotSend(),
                "data_type" => $this->physical_type_repo->getDataPhysicalType()
            ]);
        } catch (Throwable $e) {
            return $e;
        }
    }
    public function update(Request $request)
    {
        try {
            # mendapatkan display physical type
            $physical_type_display = $this->physical_type_repo->getDataPhysicalTypeCode($request->physical_type_code);
            # update
            $this->location_repo->updateLocation($request->all(), $physical_type_display, $this->dec($request->id_ubah));

            return redirect('lokasi')
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
            $response_satusehat  = $this->api_response_ss('/Location', $id);
            return view('pages.location.location-response-ss', [
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
            return view('pages.location.location-kirim-ss', [
                "data_location" => $this->location_repo->getDataLocationFind($this->dec($id)),
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
            $data_location = $this->location_repo->getDataLocationFind($this->dec($request->id));
            $data_parameter = $this->parameter_repo->getDataParameterFirst();

            $payload_location = $this->bodyLocation($data_location, $data_parameter);

            $response = $this->post_general_ss('/Location', $payload_location);
            $body_parse = json_decode($response->body());

            $satusehat_id = null;
            if ($response->successful()) {
                $satusehat_id = $body_parse->id;
            }
            # update status ke database
            $this->location_repo->updateStatusLocation($this->dec($request->id), $satusehat_id, $payload_location, $response);
            return $response;
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\GeneralTrait;
use App\Models\Organization;
use App\Models\Status;
use App\Models\Location;
use App\Models\PhysicalType;
use App\Models\Poli;
use App\Repositories\Location\LocationInterface;
use App\Repositories\Organization\OrganizationInterface;
use App\Repositories\PhysicalType\PhysicalTypeInterface;
use App\Repositories\Status\StatusInterface;
use Throwable;


class LocationController extends Controller
{

    use GeneralTrait;

    protected $location_repo;
    protected $organization_repo;
    protected $status_repo;
    protected $physical_type_repo;

    public function __construct(
        LocationInterface $locationRepository,
        OrganizationInterface $organizationRepository,
        StatusInterface $statusRepository,
        PhysicalTypeInterface $physicalTypeRepository,
    ) {
        $this->location_repo = $locationRepository;
        $this->organization_repo = $organizationRepository;
        $this->status_repo = $statusRepository;
        $this->physical_type_repo = $physicalTypeRepository;
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
            $response_satusehat  = $this->api_response_ss('Location', $id);
            return view('pages.location.location-response-ss', [
                "data_response" => $response_satusehat
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

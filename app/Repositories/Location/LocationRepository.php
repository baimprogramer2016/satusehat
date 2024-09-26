<?php

namespace App\Repositories\Location;

use Carbon\Carbon;
use App\Models\Location;
use App\Models\Poli;

class LocationRepository implements LocationInterface
{
    private $model, $poli_model;

    public function __construct()
    {
        $this->model = new Location();
        $this->poli_model = new Poli();
    }

    # mendapatkan nilai keseluruhan
    public function getDataLocation()
    {
        return $this->model->get();
    }
    public function getDataLocationByIDSS($id)
    {
        return $this->model->where('satusehat_id', $id)->first();
    }
    public function getDataLocationReadySatuSehat()
    {
        return $this->model->whereNotNull('satusehat_id')->get();
    }
    # menyimpan data
    public function storeLocation($request = [], $physical_display)
    {
        $this->model->create([
            "original_code" => $request['original_code'],
            "name"  => $request['name'],
            "status" => "active",
            "identifier_value"  => $request['name'],
            "description"  => $request['name'],
            "managing_organization" => $request['managing_organization'] ?? "",
            "telecom_phone" => config('constan.default_organization.phone'),
            "telecom_fax" => config('constan.default_organization.fax'),
            "telecom_email" => config('constan.default_organization.email'),
            "telecom_url" => config('constan.default_organization.url'),
            "address" => config('constan.default_organization.address'),
            "city" => config('constan.default_organization.city'),
            "postal_code" => config('constan.default_organization.postal_code'),
            "country" => config('constan.default_organization.country_code'),
            "extension_province" => config('constan.default_organization.province_code'),
            "extension_city" => config('constan.default_organization.city_code'),
            "extension_district" => config('constan.default_organization.district_code'),
            "extension_village" => config('constan.default_organization.village_code'),
            "extension_rt" => config('constan.default_organization.rt'),
            "extension_rw" => config('constan.default_organization.rw'),
            "physical_type_code" => $request['physical_type_code'],
            "physical_type_display" => $physical_display,
            "position_longitude" => config('constan.default_organization.position_longitude'),
            "position_latitude" => config('constan.default_organization.position_latitude'),
            "position_altitude" => config('constan.default_organization.position_altitude'),
            "satusehat_send" => $request['satusehat_send'],
        ]);

        return $this->model;
    }

    public function getDataLocationFind($id)
    {
        return $this->model->find($id);
    }
    public function deleteLocation($id)
    {
        # identifikasi data yang ingin di delete
        $delete = $this->model->find($id);
        # delete
        $delete->delete();

        return $delete;
    }

    public function updateLocation($request = [], $physical_display, $id)
    {

        $data = $this->getDataLocationFind($id);
        $data->original_code = $request['original_code'];
        $data->identifier_value = $request['name'];
        $data->description = $request['name'];
        $data->name = $request['name'];
        $data->physical_type_code = $request['physical_type_code'];
        $data->physical_type_display = $physical_display;
        $data->physical_type_display = $physical_display;
        $data->partof_id = $request['partof_id'];
        $data->satusehat_send = $request['satusehat_send'];
        $data->telecom_phone = config('constan.default_organization.phone');
        $data->telecom_fax = config('constan.default_organization.fax');
        $data->telecom_email = config('constan.default_organization.email');
        $data->telecom_url = config('constan.default_organization.url');
        $data->address = config('constan.default_organization.address');
        $data->city = config('constan.default_organization.city');
        $data->postal_code = config('constan.default_organization.postal_code');
        $data->country = config('constan.default_organization.country_code');
        $data->extension_province = config('constan.default_organization.province_code');
        $data->extension_city = config('constan.default_organization.city_code');
        $data->extension_district = config('constan.default_organization.district_code');
        $data->extension_village = config('constan.default_organization.village_code');
        $data->extension_rt = config('constan.default_organization.rt');
        $data->extension_rw = config('constan.default_organization.rw');
        $data->position_longitude = config('constan.default_organization.position_longitude');
        $data->position_latitude = config('constan.default_organization.position_latitude');
        $data->position_altitude = config('constan.default_organization.position_altitude');
        $data->update();

        return $data;
    }

    public function updateStatusLocation($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->find($id);
        $data->satusehat_id = $satusehat_id;
        $data->satusehat_request = $request;
        $data->satusehat_response = $response;
        $data->satusehat_send = ($satusehat_id != null) ? 1 : 0;
        $data->satusehat_statuscode =  ($satusehat_id != null) ? '200' : '500';
        $data->satusehat_date = Carbon::now()->format('Y-m-d H:i:s');
        $data->update();

        return $data;
    }

    public function getDataPoli()
    {
        $location_data = $this->model->select('original_code')->get();;
        return $this->poli_model->whereNotIn('original_code', $location_data)->get();
    }
}

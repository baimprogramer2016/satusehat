<?php

namespace App\Repositories\Location;

use Carbon\Carbon;
use App\Models\Location;

class LocationRepository implements LocationInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Location();
    }

    # mendapatkan nilai keseluruhan
    public function getDataLocation()
    {
        return $this->model->get();
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
        $data->managing_organization = $request['managing_organization'];
        $data->satusehat_send = $request['satusehat_send'];
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
}

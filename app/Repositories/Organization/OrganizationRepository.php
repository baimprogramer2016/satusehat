<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
use Carbon\Carbon;

class OrganizationRepository implements OrganizationInterface
{
    private $model;
    public function __construct()
    {
        $this->model = new Organization();
    }

    # untuk mendapatkan keseluruhan data
    public function getDataOrganization()
    {
        return $this->model->orderBy('id', 'ASC')->get();
    }

    # untuk bagian organization / managing organization
    public function getDataOrganizationIdSuccess()
    {
        return $this->model->where('satusehat_id', '!=', '')->get();
    }

    # dapatkan berdasarkan ID , untuk hapus dan update
    public function getDataOrganizationFind($id)
    {
        return $this->model->find($id);
    }

    # simpan data
    public function storeOrganization($request = [])
    {
        $this->model->create([
            "original_code" => $request['original_code'],
            "name"  => $request['name'],
            "identifier_value"  => $request['name'],
            "partof_id" => $request['partof_id'] ?? '',
            "type_code" => config('constan.default_organization.type_code'),
            "type_display" => config('constan.default_organization.type_display'),
            "phone" => config('constan.default_organization.phone'),
            "email" => config('constan.default_organization.email'),
            "url" => config('constan.default_organization.url'),
            "address" => config('constan.default_organization.address'),
            "city" => config('constan.default_organization.city'),
            "country_code" => config('constan.default_organization.country_code'),
            "postal_code" => config('constan.default_organization.postal_code'),
            "province_code" => config('constan.default_organization.province_code'),
            "city_code" => config('constan.default_organization.city_code'),
            "district_code" => config('constan.default_organization.district_code'),
            "village_code" => config('constan.default_organization.village_code'),
            "satusehat_send" => $request['satusehat_send'],
            "active" => 1,
        ]);

        return $this->model;
    }

    public function deleteOrganization($id)
    {
        # identifikasi data yang ingin di delete
        $delete = $this->model->find($id);
        # delete
        $delete->delete();

        return $delete;
    }

    public function updateOrganization($request = [], $id)
    {
        $data = $this->getDataOrganizationFind($id);
        $data->original_code = $request['original_code'];
        $data->name = $request['name'];
        $data->identifier_value = $request['name'];
        $data->partof_id = $request['partof_id'];
        $data->satusehat_send = $request['satusehat_send'];
        $data->update();

        return $data;
    }

    public function updateStatusOrganization($id, $satusehat_id, $request, $response)
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

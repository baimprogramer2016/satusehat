<?php

namespace App\Repositories\Parameter;

use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterRepository implements ParameterInterface
{

    private $model;
    private $data_parameter;

    public function __construct()
    {
        $this->model = new Parameter();
    }
    public function getDataParameterFirst()
    {
        return $this->model->first();
    }
    public function updateParamter($request = [])
    {
        $this->data_parameter = $this->model->first();
        $this->data_parameter->client_id = $request['client_id'];
        $this->data_parameter->client_secret = $request['client_secret'];
        $this->data_parameter->auth_url = $request['auth_url'];
        $this->data_parameter->base_url = $request['base_url'];
        $this->data_parameter->consent_url = $request['consent_url'];
        $this->data_parameter->generate_token_url = $request['generate_token_url'];
        $this->data_parameter->organization_id = $request['organization_id'];
        $this->data_parameter->username = $request['username'];
        $this->data_parameter->pass = $request['pass'];
        $this->data_parameter->farmasi_id = $request['farmasi_id'];
        $this->data_parameter->access_token = $request['access_token'];
        $this->data_parameter->update();

        return $this->data_parameter;
    }
}

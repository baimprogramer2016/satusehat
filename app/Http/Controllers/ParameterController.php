<?php

namespace App\Http\Controllers;

use App\Repositories\Parameter\ParameterInterface;
use Illuminate\Http\Request;
use Throwable;

class ParameterController extends Controller
{

    protected $data_parameter, $parameter_repo;
    public function __construct(ParameterInterface $parametereRepository)
    {
        $this->parameter_repo = $parametereRepository;
    }
    public function index()
    {
        try {
            return view('pages.parameter.parameter', [
                "data_parameter" => $this->parameter_repo->getDataParameterFirst()
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function ubah()
    {
        return view("pages.parameter.parameter-ubah", [
            "data_parameter" => $this->parameter_repo->getDataParameterFirst()
        ]);
    }

    public function update(Request $request)
    {
        try {
            $this->parameter_repo->updateParamter($request->all());
            return redirect('parameter')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with("warna", 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function lihat()
    {
        return view("pages.parameter.parameter-lihat", [
            "data_parameter" => $this->parameter_repo->getDataParameterFirst()
        ]);
    }
}

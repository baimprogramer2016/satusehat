<?php

namespace App\Http\Controllers;

use App\Repositories\Sinkronisasi\SinkronisasiInterface;
use Illuminate\Http\Request;
use Throwable;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;

class SinkronisasiController extends Controller
{
    use ApiTrait;

    protected $sinkronisasi_repo;

    public function __construct(SinkronisasiInterface $sinkronisasiInterface)
    {
        $this->sinkronisasi_repo = $sinkronisasiInterface;
    }
    public function index()
    {
        return view("pages.sinkronisasi.sinkronisasi", [
            "data_sinkronisasi" => $this->sinkronisasi_repo->getDataSinkronisasi(),
        ]);
    }

    public function tambah()
    {
        return view('pages.sinkronisasi.sinkronisasi-tambah');
    }

    public function simpan(Request $request)
    {
        try {

            $this->sinkronisasi_repo->insertSinkronisasi($request->all());

            return redirect('sinkronisasi')
                ->with("pesan", config('constan.message.form.success_saved'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

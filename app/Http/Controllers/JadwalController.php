<?php

namespace App\Http\Controllers;

use App\Repositories\Jadwal\JadwalInterface;
use Illuminate\Http\Request;
use Throwable;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use App\Traits\JsonTrait;

class JadwalController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    use JsonTrait;

    protected $jadwal_repo;

    public function __construct(
        JadwalInterface $jadwalInterface
    ) {
        $this->jadwal_repo = $jadwalInterface;
    }
    public function index()
    {
        return view("pages.jadwal.jadwal", [
            "data_jadwal" => $this->jadwal_repo->getDataJadwal(),
        ]);
    }

    public function ubah(Request $request, $id)
    {
        return view('pages.jadwal.jadwal-ubah', [
            "data_jadwal" => $this->jadwal_repo->getDataJadwalById($this->dec($id)),
        ]);
    }

    public function update(Request $request)
    {
        try {
            $this->jadwal_repo->updateJadwal($request->cron, $request->status, $this->dec($request->id_ubah));
            return redirect('jadwal')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

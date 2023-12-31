<?php

namespace App\Http\Controllers;

use App\Repositories\Upload\UploadInterface;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Throwable;

class UploadController extends Controller
{
    use GeneralTrait;
    public $upload_repo;

    public function __construct(UploadInterface $uploadInterface)
    {
        $this->upload_repo = $uploadInterface;
    }
    public function index()
    {
        return view("pages.upload.upload", [
            "data_upload" => $this->upload_repo->getDataUpload()
        ]);
    }


    public function ubah($id)
    {
        return view("pages.upload.upload-ubah", [
            "data_upload" => $this->upload_repo->getDataUploadFirst($this->dec($id))
        ]);
    }


    public function update(Request $request)
    {
        try {

            $file = $request->file('file_upload');

            if ($file->getClientOriginalExtension() != $request->tipe) {

                return redirect('upload')
                    ->with("pesan", config('constan.message.form.error_type'))
                    ->with('warna', 'danger');
            } else {
                $fileName = $request->name . '.' . $file->getClientOriginalExtension();;

                // Pindahkan file ke dalam folder public
                $file->move(public_path() . '/uploads', $fileName);

                return redirect('upload')
                    ->with("pesan", config('constan.message.form.success_updated'))
                    ->with('warna', 'success');
            }
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

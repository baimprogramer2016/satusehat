<?php

namespace App\Repositories\Upload;

use App\Models\Upload;

class UploadRepository implements UploadInterface
{
    private $model;
    public function __construct(Upload $upload)
    {
        $this->model = $upload;
    }

    # untuk mendapatkan keseluruhan data
    public function getDataUpload()
    {
        return $this->model->get();
    }
    public function getDataUploadFirst($id)
    {
        return $this->model->find($id);
    }

    public function uploadData($request = [], $id)
    {
        $data = $this->getDataUploadFirst($id);
        $data->update();

        return $data;
    }
}

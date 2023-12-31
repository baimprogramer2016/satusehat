<?php

namespace App\Repositories\Upload;

interface UploadInterface
{
    public function getDataUpload();
    public function getDataUploadFirst($id);
    public function uploadData($request = [], $id);
}

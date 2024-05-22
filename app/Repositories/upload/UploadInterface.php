<?php

namespace App\Repositories\Upload;

interface UploadInterface
{
    public function getDataUploadx();
    public function getDataUploadFirst($id);
    public function uploadData($request = [], $id);
}

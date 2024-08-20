<?php

namespace App\Repositories\RencanaTindakLanjutMaster;


interface RencanaTindakLanjutMasterInterface
{
    public function getQuery();
    public function getRencanaTindakLanjutMasterId($id);

    public function updateRencanaTindakLanjutMasterCode($param);
}

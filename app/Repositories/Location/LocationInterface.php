<?php

namespace App\Repositories\Location;

interface LocationInterface
{
    public function getDataLocation();
    public function storeLocation($request =  [], $physical_display);
    public function getDataLocationFind($id);
    public function deleteLocation($id);
    public function updateLocation($request = [], $physical_display, $id);
    public function updateStatusLocation($id, $satusehat_id, $request, $response);
    public function getDataPoli();
}

<?php

namespace App\Repositories\Composition;

interface CompositionInterface
{
    public function getQuery();
    public function getDataCompositionById($id);
    public function getDataCompositionByOriginalCode($original_code);
    public function getDataCompositionBundleByOriginalCode($original_code);
    public function updateDataBundleCompositionJob($param = []);

    public function getDataCompositionFind($id);
    public function updateStatusComposition($id, $satusehat_id, $request, $response);

    public function getDataCompositionReadyJob();
}

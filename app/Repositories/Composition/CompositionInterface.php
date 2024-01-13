<?php

namespace App\Repositories\Composition;

interface CompositionInterface
{
    public function getQuery();
    public function getDataCompositionById($id);
    public function getDataCompositionByOriginalCode($original_code);
}

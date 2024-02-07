<?php

namespace App\Repositories\CategoryRequest;

interface CategoryRequestInterface
{
    public function getQuery();

    public function getCategoryRequest();

    public function getDataCategoryRequestFind($id);
    public function updateCategoryRequest($request = [], $id);
}

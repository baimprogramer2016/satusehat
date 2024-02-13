<?php

namespace App\Repositories\CategoryRequest;


use App\Models\CategoryRequest;
use App\Repositories\CategoryRequest\CategoryRequestInterface;
use Carbon\Carbon;

class CategoryRequestRepository implements CategoryRequestInterface
{
    private $model;
    public function __construct()
    {
        $this->model = new CategoryRequest();
    }



    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }
    public function getCategoryRequest()
    {
        return $this->model->where('display', '!=', '')->get();
    }

    public function getDataCategoryRequestFind($id)
    {
        return $this->model->where('display', $id)->first();
    }
    public function updateCategoryRequest($request = [], $id)
    {
        return $this->model->where('display', $id)->update([
            'payload' => $request['payload'],
            'field' => $request['field'],
            'diagnostic_report_code' => $request['diagnostic_report_code'],
            'diagnostic_report_display' => $request['diagnostic_report_display'],
        ]);
    }
}

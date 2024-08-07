<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRequest\CategoryRequest;
use App\Repositories\CategoryRequest\CategoryRequestInterface;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CategoryRequestController extends Controller
{
    use GeneralTrait;
    protected $category_request_repo;

    public function __construct(
        CategoryRequestInterface $categoryRequestInterface
    ) {
        $this->category_request_repo = $categoryRequestInterface;
    }

    public function index()
    {
        return view("pages.category-request.category-request", [
            "data_category_request" => $this->category_request_repo->getCategoryRequest(),
        ]);
    }

    public function ubah(Request $request, $display)
    {

        return view('pages.category-request.category-request-ubah', [
            "data_category_request" => $this->category_request_repo->getDataCategoryRequestFind($this->dec($display)),
        ]);
    }
    public function update(Request $request)
    {

        try {
            $this->category_request_repo->updateCategoryRequest($request, $this->dec($request->id_ubah));
            return redirect('category-request')
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}

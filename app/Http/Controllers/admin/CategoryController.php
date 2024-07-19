<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryService;
use App\Traits\JsonResponseTrait;

class CategoryController extends Controller
{
    use JsonResponseTrait;

    public $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categories = $this->categoryService->getAll();
        return view("admin.category.list", compact('categories'));
    }

    public function create()
    {
        return view("admin.category.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required|unique:categories",
            "status" => 'required|boolean',
        ]);

        if ($validator->passes()) {

            return $this->categoryService->create($request->all());

        } else {
            return $this->validationErrorResponse(false, $validator->errors());
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = $this->categoryService->find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required",
            "status" => 'required|boolean',
        ]);

        if ($validator->passes()) {

            return $this->categoryService->update($request->category, $request->all());

        } else {
            return $this->validationErrorResponse(false, $validator->errors());
        }
    }

    public function destroy(Request $request, $id)
    {
        return $this->categoryService->delete($id);
    }
}

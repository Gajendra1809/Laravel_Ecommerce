<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index()
    {
        $subcategories = SubCategory::latest()->paginate(10);
        return view("admin.subcategory.list", compact("subcategories"));
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view("admin.subcategory.create", compact("categories"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required|unique:sub_categories",
            "category_id" => 'required',
            "status" => 'required|boolean',
        ]);

        if ($validator->passes()) {
            SubCategory::create($request->all());

            return response()->json([
                'status' => true,
                'redirect' => route('sub-categories.index'),
                'message' => 'Sub Category created successfully',
            ]);

        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $subcategory = SubCategory::find($categoryId);
        $categories = Category::orderBy('name', 'ASC')->get();

        if (empty($subcategory)) {
            return redirect()->route('sub-categories.index');
        }
        return view('admin.subcategory.edit', compact('subcategory','categories'));
    }

    public function update(Request $request)
    {
        $subcategory = SubCategory::where('id', $request->category)->first();

        if (empty($subcategory)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required",
            "status" => 'required|boolean',
            "category_id" => 'required',
        ]);

        if ($validator->passes()) {
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->showHome = $request->showHome;
            $subcategory->category_id = $request->category_id;
            $subcategory->save();

            return response()->json([
                'status' => true,
                'redirect' => route('sub-categories.index'),
                'message' => 'SubCategory updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $subcategory->delete();

        return response()->json([
            'status' => true,
            'redirect' => route('sub-categories.index'),
            'message' => 'Category deleted successfully',
        ]);
    }
}


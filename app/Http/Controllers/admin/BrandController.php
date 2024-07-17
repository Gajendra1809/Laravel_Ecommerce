<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view("admin.brands.list",compact("brands"));
    }
    public function create(){
        return view("admin.brands.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required|unique:brands",
            "status" => 'required|boolean',
        ]);

        if ($validator->passes()) {
            Brand::create($request->all());

            return response()->json([
                'status' => true,
                'redirect' => route('brands.index'),
                'message' => 'Brand created successfully',
            ]);

        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($brandId, Request $request)
    {
        $brand = Brand::find($brandId);

        if (empty($brand)) {
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request)
    {
        $brand = Brand::where('id', $request->brand)->first();

        if (empty($brand)) {
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
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'redirect' => route('brands.index'),
                'message' => 'Brand updated successfully',
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
        $brand = Brand::findOrFail($id);

        $brand->delete();

        return response()->json([
            'status' => true,
            'redirect' => route('brands.index'),
            'message' => 'Brand deleted successfully',
        ]);
    }
}

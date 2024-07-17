<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(5);
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
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            //image save here
            if (!empty($request->image_id)) {

                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->image);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempImage->image;
                $dpath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($spath, $dpath);

                //generate image thumbnail
                $dpath = public_path() . '/uploads/category/thumb/' . $newImageName;

                $img = Image::read($spath);
                $img->resize(450, 600);
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();
            }

            return response()->json([
                'status' => true,
                'redirect' => route('categories.index'),
                'message' => 'Category created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request)
    {
        $category = Category::where('id', $request->category)->first();

        if (empty($category)) {
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
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;
            //image save here
            if (!empty($request->image_id)) {

                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->image);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempImage->image;
                $dpath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($spath, $dpath);

                //generate image thumbnail
                $dpath = public_path() . '/uploads/category/thumb/' . $newImageName;

                $img = Image::read($spath);
                $img->resize(450, 600);
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();

                //delete old image
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }

            return response()->json([
                'status' => true,
                'redirect' => route('categories.index'),
                'message' => 'Category updated successfully',
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
        $category = Category::findOrFail($id);

        // Delete the category image files if they exist
        if ($category->image) {
            $thumbPath = public_path('uploads/category/thumb/' . $category->image);
            $imagePath = public_path('uploads/category/' . $category->image);

            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'redirect' => route('categories.index'),
            'message' => 'Category deleted successfully',
        ]);
    }
}

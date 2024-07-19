<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ProductService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\SubcategoryService;
use App\Traits\JsonResponseTrait;

class ProductController extends Controller
{
    use JsonResponseTrait;

    public $productService;
    public $brandService;
    public $categoryService;
    public $subcategoryService;

    public function __construct(

        ProductService $productService,
        BrandService $brandService,
        CategoryService $categoryService,
        SubcategoryService $subcategoryService

        ){

        $this->productService = $productService;
        $this->brandService = $brandService;
        $this->categoryService = $categoryService;
        $this->subcategoryService = $subcategoryService;

    }

    public function index()
    {
        $products = $this->productService->getAll();
        return view("admin.products.list", compact("products"));
    }

    public function create()
    {
        $categories = $this->categoryService->getByOrder();
        $brands = $this->brandService->getByOrder();
        return view("admin.products.create", compact("categories", "brands"));
    }

    public function store(Request $request)
    {
        if ($request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ]);

        if ($validator->passes()) {
           
            return $this->productService->create($request->all());

        } else {
            return $this->validationErrorResponse(false, $validator->errors());
        }
    }

    public function edit(Request $request, $id)
    {
        $product = $this->productService->find($id);

        if (empty($product)) {

            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        $productImages = $this->productService->getProductImages($product->id);
        $subCategories = $this->subcategoryService->getSubCategories($product->category_id);
        $categories = $this->categoryService->getByOrder();
        $brands = $this->brandService->getByOrder();

        return view("admin.products.edit", compact("product", "categories", "brands", "subCategories", "productImages"));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if ($request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            return $this->productService->update($id, $request->all());
        } else {
            return $this->validationErrorResponse(false, $validator->errors());
        }
    }

    public function destroy($id)
    {
        return $this->productService->delete($id);
    }
}

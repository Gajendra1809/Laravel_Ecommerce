<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubcategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get("/", [FrontController::class,'index'])->name('front.home');

Route::get('/admin/login', [AdminLoginController::class,'index'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class,'login'])->name('admin.login.submit');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    //category routes
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
    Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
    Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
    Route::delete('/categories/{id}',[CategoryController::class,'destroy'])->name('categories.delete');

    //subcategory routes
    Route::get('sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('sub-categories/store', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
    Route::get('/sub-categories/{subcategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
    Route::put('/sub-categories/{category}',[SubCategoryController::class,'update'])->name('sub-categories.update');
    Route::delete('/sub-categories/{id}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');

    //brand routes
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
    Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
    Route::put('/brands/{brand}',[BrandController::class,'update'])->name('brands.update');
    Route::delete('/brands/{id}',[BrandController::class,'destroy'])->name('brands.delete');

    //product routes

    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');

    
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products',[ProductController::class,'index'])->name('products.index');
    Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
    Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
    Route::delete('/products/{id}',[ProductController::class,'destroy'])->name('products.delete');

    Route::get('/product-subcategories',[ProductSubcategoryController::class,'index'])->name('product-subcategories.index');
    Route::post('product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
    Route::delete('product-images', [ProductImageController::class, 'destroy'])->name('product-images.delete');

    Route::post('upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');
    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if(!empty($request->title)){
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');
});

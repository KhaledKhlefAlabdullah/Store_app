<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\UsersController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\CategoryController;
use \App\Http\Controllers\Api\OrdersController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('users',[UsersController::class,'index'])->name('users')->middleware('admin');

    Route::post('add-product',[ProductController::class,'store'])->name('add-product');

    Route::post('add-category',[CategoryController::class,'store'])->name('add-category');

    Route::get('get-orders-and-details',[OrdersController::class,'index'])->name('get-orders-and-details')->middleware('admin');

    Route::post('create-order',[OrdersController::class,'create_order'])->name('create-order');

    Route::post('add-order-detail',[OrdersController::class,'add_order_details'])->name('add-order-detail');

    Route::post('edit-order-detail',[OrdersController::class,'update_order_detail'])->name('edit-order-detail');

});

Route::get('products',[ProductController::class,'index'])->name('products');

Route::get('products_by_category',[ProductController::class,'category_products'])->name('products_by_category');

Route::get('categories',[CategoryController::class,'index'])->name('categories');

require __DIR__.'/auth.php';

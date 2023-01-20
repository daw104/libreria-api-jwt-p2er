<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});

//OrderController routing
Route::controller(OrderController::class)->group(function() {
    Route::post('order', 'storeOrder');
    Route::get('order/{order}', 'listOrders');
    Route::get('orders/{user}', 'listUser');
    Route::delete('orders/{id}/delete', 'destroy');
    Route::put('order/{id}/update', 'update');
    //rutas de los products
    Route::post('order/{order}/products/{product}/order', 'registrarUnaComprar');
    Route::get('order/{order}/products', 'listProduct');
});


Route::controller(ProductController::class)->group(function() {
    Route::post('product', 'store');
    Route::get('products', 'index');
    Route::get('products/{id}', 'show');
    Route::delete('products/{id}/delete', 'destroy');
    Route::put('product/{id}/update', 'update');
    Route::get('product/{product}/orders', 'listOrder');
});

/*listado de productos paginados*/
Route::get('/productos/',
    [\App\Http\Controllers\ProductController::class, 'list']);

//Ruta SellerController
Route::controller(SellerController::class)->group(function() {
    Route::post('seller', 'store');
    Route::get('sellers', 'index');
    Route::put('seller/{id}/update', 'update');
    Route::delete('sellers/{id}/delete', 'destroy');
});

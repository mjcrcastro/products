<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\InvoicesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Products routes
Route::resource('products', ProductsController::class);
//Route for responding vis Ajax
Route::get('products_ajax',[App\Http\Controllers\ProductsController::class, 'productsAjax']);

Route::get('allproductsjson', [App\Http\Controllers\ProductsController::class, 'allProductsJson']);

Route::get('getcsv', [App\Http\Controllers\ProductsController::class,'exportToCsv']);

//Products routes
Route::resource('invoices', InvoicesController::class);

//Products routes
Route::resource('providers', ProvidersController::class);
Route::get('providers_ajax',[App\Http\Controllers\ProvidersController::class, 'providersAjax']);
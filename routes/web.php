<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\BuyersController;
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
//***************Products routes
Route::resource('products', ProductsController::class);
//Route for responding via Ajax to datatables
Route::get('products_ajax',[App\Http\Controllers\ProductsController::class, 'productsAjax']);
//route respond to Invoice Mobile App
Route::get('allproductsjson', [App\Http\Controllers\ProductsController::class, 'allProductsJson']);
//route to feed a select2 select control
Route::get('select2ajax',[App\Http\Controllers\ProductsController::class, 'productsSelect2Json']);

//**************Invoices routes
Route::resource('invoices', InvoicesController::class);
//Route to provide list of invoices to DataTables
Route::get('invoices_index', [App\Http\Controllers\InvoicesController::class, 'indexInvoicesAjax']);

//**************Providers routes
Route::resource('providers', ProvidersController::class);
Route::get('providers_ajax',[App\Http\Controllers\ProvidersController::class, 'providersAjax']);

//**************Purchases routes
Route::resource('purchases', PurchasesController::class);
Route::get('select_providers_ajax',[App\Http\Controllers\ProvidersController::class, 'providersSelect2Json']);
Route::get('purchases_index',[App\Http\Controllers\PurchasesController::class, 'indexPurchasesAjax']);


//**************Buyers routes
Route::resource('buyers', BuyersController::class);
Route::get('buyers_ajax',[App\Http\Controllers\BuyersController::class, 'buyersAjax']);
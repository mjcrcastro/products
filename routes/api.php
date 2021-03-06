<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route to receive invoices from the Invoices mobile app
Route::post('invoice', [App\Http\Controllers\InvoicesController::class, 'receiveInvoicesJson']);

Route::get('get_purchase/{id}', [App\Http\Controllers\PurchasesController::class, 'returnPurchase']);

Route::post('header_test',[App\Http\Controllers\BuyersController::class, 'headerTesterPost']);
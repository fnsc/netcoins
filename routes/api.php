<?php

use Crypto\Presenters\Http\Controllers\Api\IndexController;
use Crypto\Presenters\Http\Controllers\Api\PriceController;
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

Route::group([
    'prefix' => 'v1/crypto',
    'as' => 'api.crypto',
], function () {
    Route::get('/list', [IndexController::class, 'index'])->name('.index');
    Route::get('/price', [PriceController::class, 'price'])->name('.price');
});

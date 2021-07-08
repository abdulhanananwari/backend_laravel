<?php

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

$middleware = [];

Route::group(['prefix' => 'payment-gateway', 'namespace' => 'App\Http\Controllers', 'middleware' => $middleware], function () {
    //Generate Token untuk snap
    Route::post('generate-token-snap', ['uses' => 'PaymentGatewayController@generateTokenSnap']);
    //untuk handle after transaction
    Route::post('notification', ['uses' => 'PaymentGatewayController@notification']);
});

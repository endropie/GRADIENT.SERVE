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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('user', 'AuthController@show');
        });
    });

    Route::get('items/serial/{id}', 'ItemController@serial');
    Route::apiResource('items', 'ItemController');

    Route::apiResource('receives', 'ReceiveController');
    Route::apiResource('loadings', 'LoadingController');
});

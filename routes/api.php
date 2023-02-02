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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login')->name('auth.login');
    Route::post('logout', 'AuthController@logout')->name('auth.logout');
    Route::post('refresh', 'AuthController@refresh')->name('auth.refresh');
    Route::post('me', 'AuthController@me')->name('auth.me');

});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/client', 'ClientController');
    Route::apiResource('/sample', 'AccessSampleController');
    Route::get('/sample/{sample}/valid', 'AccessSampleController@valid');

    Route::get('/type', 'AccessTypeController@index');
    Route::get('/type/{type}', 'AccessTypeController@show');
    Route::get('/type/{type}/sample', 'AccessTypeController@showSample');

    Route::apiResource('/client/{client}/access', 'AccessController');
    Route::get('/client/{client}/access/{id}/compil', 'AccessController@compil');
});

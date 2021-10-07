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


Route::group(['api'], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post('login', [\App\Http\Controllers\Api\User\AuthController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\Api\User\AuthController::class, 'register']);

        Route::group(['middleware' => 'auth.guard:user-api'], function () {
            Route::post('logout', [\App\Http\Controllers\Api\User\AuthController::class, 'logout']);
            Route::post('refresh', [\App\Http\Controllers\Api\User\AuthController::class, 'refresh']);
            Route::post('me', [\App\Http\Controllers\Api\User\AuthController::class, 'me']);
            Route::post('mytoken', [\App\Http\Controllers\Api\User\AuthController::class, 'GetMyToken']);
        });
    });

    /***************************************  Begin Consents Group ***************************************/
    Route::group(['prefix' => 'consents'], function () {
        Route::group(['middleware' => 'auth.guard:user-api'], function () {

            Route::post('create', [\App\Http\Controllers\Api\ConsentController::class, 'CreateConsent']);
            Route::get('all/{pag_count?}', [\App\Http\Controllers\Api\ConsentController::class, 'GetConsents']);
            Route::post('search/{pag_count?}/{key?}/', [\App\Http\Controllers\Api\ConsentController::class, 'SearchConsents']);

        });
    });
    /***************************************  End Consents Group ***************************************/

});

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('user')->controller(AuthController::class)->group(function () {
    Route::post('register','store');
    Route::post('login','login');

    Route::middleware('auth:sanctum')->group( function(){

        Route::get('view','show');
        Route::get('logout','logout');
        Route::post('update','update');
    });
});

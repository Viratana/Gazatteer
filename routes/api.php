<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("login",[UserController::class, 'login']);
Route::post("register",[UserController::class,'register']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::apiResource('location', LocationController::class);
    Route::get('/location/{location}', [LocationController::class, 'show']);
    Route::get('location/search/{term}', [LocationController::class, 'search']);
});




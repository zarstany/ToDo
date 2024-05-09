<?php

use App\Http\V1\Controllers\Auth\LoginController;
use App\Http\V1\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes Without authentication
Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

//Routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/welcome', function () {
        return response()->json('Hola Ela');
    });
});

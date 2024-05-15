<?php

use App\Http\V1\Controllers\Auth\LoginController;
use App\Http\V1\Controllers\Auth\RegisterController;
use App\Http\V1\Controllers\Tasks\CreateTaskController;
use App\Http\V1\Controllers\Tasks\UpdateTaskController;
use Illuminate\Support\Facades\Route;

// Routes Without authentication
Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

//Routes with authentication
Route::middleware('auth:sanctum')->group(function () {

    Route::post('tasks/store', CreateTaskController::class);
    Route::put('tasks/update/{taskId}', UpdateTaskController::class);
});

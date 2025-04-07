<?php

use App\Http\V1\Controllers\Auth\LoginController;
use App\Http\V1\Controllers\Auth\RegisterController;
use App\Http\V1\Controllers\Tasks\CreateTaskController;
use App\Http\V1\Controllers\Tasks\UpdateTaskController;
use Illuminate\Support\Facades\Route;
use App\Http\V1\Controllers\Tasks\DeleteTaskController;
use App\Http\V1\Controllers\Tasks\UserTasksController;

// Routes Without authentication
Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

//Routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('tasks',UserTasksController::class);
    Route::post('tasks/store', CreateTaskController::class);
    Route::put('tasks/update', UpdateTaskController::class);
    Route::delete('tasks/delete',DeleteTaskController::class);
});

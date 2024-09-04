<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::post('register', [AuthController::class, 'register']);
Route::post('login-start', [AuthController::class, 'loginStart']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('test-token', [AuthController::class, 'testToken']);
});


<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::post('register', [AuthController::class, 'register']);
Route::post('login-start', [AuthController::class, 'loginStart']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('test-token', [AuthController::class, 'testToken']);



    Route::middleware('isAdmin')->group(function () {
        //exams
        Route::post('exams/add-exam', [ExamController::class, 'store']);
        Route::put('exams/update-exam/{id}', [ExamController::class, 'update']);
        Route::post('questions/add-question-multiple', [QuestionController::class, 'storeMultiple']);


    });
});


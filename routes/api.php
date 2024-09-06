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




    Route::middleware('isAdmin')->group(function () {
        //exams
        Route::prefix('exams')->group(function () {
            Route::post('add-exam', [ExamController::class, 'store']);
            Route::put('update-exam/{id}', [ExamController::class, 'update']);
        });

        //questions
        Route::prefix('questions')->group(function () {
            Route::post('add-question-multiple', [QuestionController::class, 'storeMultiple']);
            Route::put('update-question/{id}', [QuestionController::class, 'update']);
            Route::delete('delete-question/{id}', [QuestionController::class, 'delete']);
        });

        Route::get('test-token', [AuthController::class, 'testToken']);

    });
});


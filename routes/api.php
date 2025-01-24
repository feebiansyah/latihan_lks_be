<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;



Route::group(['middleware' => 'guest'], function () {
    Route::post('/v1/auth/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);


    //form routes
    Route::post('/v1/forms', [FormController::class, 'store']);
    Route::get('/v1/forms', [FormController::class, 'index']);
    Route::get('/v1/forms/{slug}', [FormController::class, 'show']);
    //end form routes


    //questions routes
    Route::post('/v1/forms/{formSlug}/questions', [QuestionController::class, 'store']);
    Route::delete('/v1/forms/{formSlug}/questions/{questionId}', [QuestionController::class, 'destroy']);
    //end questions routes

    //responses routes
    Route::post('/v1/forms/{formSlug}/responses', [ResponseController::class, 'store']);
    Route::get('/v1/forms/{formSlug}/responses', [ResponseController::class, 'index']);
    //end responses routes


});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\QuestionController;

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
    Route::post('/v1/forms/{form-slug}/questions', [QuestionController::class, 'store']);
    


    //end questions routes


});

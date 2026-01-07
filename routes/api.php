<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('activities', ActivityController::class);
Route::apiResource('benefits', BenefitController::class);
Route::apiResource('news', NewsController::class);
Route::apiResource('departemens', DepartemenController::class);
Route::apiResource('faqs', FaqController::class);
Route::apiResource('prokers', ProkerController::class);
Route::apiResource('aspirations', AspirationController::class);
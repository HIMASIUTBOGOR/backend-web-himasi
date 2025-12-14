<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\UserManagement\AuthController;

// Authenticated routes 
Route::prefix('auth')->group(function () {
    Route::post('/sign-in',[AuthController::class, 'signIn']);
    Route::post('/sign-out',[AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/me',[AuthController::class, 'me'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/roles/{id}/menus',[AuthController::class, 'assignMenuPermission']);
    Route::get('/me/menus',[AuthController::class, 'myMenus']);

    Route::get('/users', function () {
        return 'USER LIST';
    })->middleware('permission:user.view, menu.users.list');
    Route::post('/users', function () {
        return 'CREATE USER';
    })->middleware('permission:user.create');
    Route::delete('/users/{id}', function () {
        return 'DELETE USER';
    })->middleware('role:admin');
});



Route::middleware(['auth:sanctum', 'role:superadmin,admin'])->group(function () {
    Route::get('/roles', [AuthController::class, 'roles']);
    Route::get('/permissions', [AuthController::class, 'permissions']);

    Route::post('/users/{id}/role', [AuthController::class, 'assignRole']);
    Route::post('/roles/{id}/permissions', [AuthController::class, 'assignPermission']);
});

Route::apiResource('activities', ActivityController::class);
Route::apiResource('benefits', BenefitController::class);
Route::apiResource('news', NewsController::class);
Route::apiResource('departemens', DepartemenController::class);
Route::apiResource('faqs', FaqController::class);
Route::apiResource('prokers', ProkerController::class);
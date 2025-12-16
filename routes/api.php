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
use App\Http\Controllers\UserManagementController;

// Authenticated routes 
Route::prefix('auth')->group(function () {
    Route::post('/sign-in',[AuthController::class, 'signIn']);
    Route::post('/sign-out',[AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/me',[AuthController::class, 'me'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get('/roles/{id}/menus',[AuthController::class, 'assignMenuPermission']);
    Route::get('/me/menus',[AuthController::class, 'myMenus']);

    // Route::get('/users', function () {
    //     return 'USER LIST';
    // })->middleware('permission:user.view, menu.users.list');
    // Route::post('/users', function () {
    //     return 'CREATE USER';
    // })->middleware('permission:user.create');
    // Route::delete('/users/{id}', function () {
    //     return 'DELETE USER';
    // })->middleware('role:admin');
});



Route::middleware(['auth:sanctum'])->group(function () {
    // Management Permission
    Route::get('/permissions', [UserManagementController::class, 'permissions'])->middleware('permission:permission.view');
    Route::post('/permission', [UserManagementController::class, 'storePermission'])->middleware('permission:permission.create');
    Route::put('/permission/{id}', [UserManagementController::class, 'updatePermission'])->middleware('permission:permission.edit');
    Route::delete('/permission/{id}', [UserManagementController::class, 'deletePermission'])->middleware('permission:permission.delete');

    // Management Role Permissions
    Route::get('/roles', [UserManagementController::class, 'roles'])->middleware('permission:role.view');
    Route::post('/role', [UserManagementController::class, 'storeRole'])->middleware('permission:role.create');
    Route::put('/role/{id}', [UserManagementController::class, 'updateRole'])->middleware('permission:role.edit');
    Route::delete('/role/{id}', [UserManagementController::class, 'deleteRole'])->middleware('permission:role.delete');

    // Management User
    Route::get('/users', [UserManagementController::class, 'users'])->middleware('permission:user.view');
    Route::post('/user', [UserManagementController::class, 'storeUser'])->middleware('permission:user.create');
    Route::put('/user/{id}', [UserManagementController::class, 'updateUser'])->middleware('permission:user.edit');
    Route::delete('/user/{id}', [UserManagementController::class, 'deleteUser'])->middleware('permission:user.delete');

    // Menu Management
    Route::get('/menus', [UserManagementController::class, 'menus']);
    Route::post('/menu', [UserManagementController::class, 'storeMenu']);
    Route::put('/menu/{id}', [UserManagementController::class, 'updateMenu']);
    Route::delete('/menu/{id}', [UserManagementController::class, 'deleteMenu']);
    
});

Route::apiResource('activities', ActivityController::class);
Route::apiResource('benefits', BenefitController::class);
Route::apiResource('news', NewsController::class);
Route::apiResource('departemens', DepartemenController::class);
Route::apiResource('faqs', FaqController::class);
Route::apiResource('prokers', ProkerController::class);
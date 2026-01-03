<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\EnumerationController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserManagement\AuthController;
use App\Http\Controllers\UserManagementController;

// Authenticated routes 
Route::prefix('auth')->group(function () {
    Route::post('/sign-in',[AuthController::class, 'signIn']);
    Route::post('/sign-out',[AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/me',[AuthController::class, 'me'])->middleware('auth:sanctum');
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
    Route::get('/menus', [UserManagementController::class, 'menus'])->middleware('permission:menu.view');
    Route::post('/menu', [UserManagementController::class, 'storeMenu'])->middleware('permission:menu.create');
    Route::put('/menu/{id}', [UserManagementController::class, 'updateMenu'])->middleware('permission:menu.edit');
    Route::delete('/menu/{id}', [UserManagementController::class, 'deleteMenu'])->middleware('permission:menu.delete');

    // Enumeration Management
    Route::get('/enumerations', [EnumerationController::class, 'index'])->middleware('permission:enumeration.view');    
    Route::get('/enumeration/byKey', [EnumerationController::class, 'enumerations'])->middleware('permission:enumeration.byKey');
    Route::post('/enumeration', [EnumerationController::class, 'storeEnumeration'])->middleware('permission:enumeration.create');
    Route::put('/enumeration/{id}', [EnumerationController::class, 'updateEnumeration'])->middleware('permission:enumeration.edit');
    Route::delete('/enumeration/{id}', [EnumerationController::class, 'deleteEnumeration'])->middleware('permission:enumeration.delete');

    // Content Manamgement System
    // Activity  AKA Kegiatan
    Route::get('/activities', [ActivityController::class, 'index'])->middleware('permission:cms.activity.view');
    Route::post('/activity', [ActivityController::class, 'store'])->middleware('permission:cms.activity.create');
    Route::put('/activity/{id}', [ActivityController::class, 'update'])->middleware('permission:cms.activity.edit');
    Route::delete('/activity/{id}', [ActivityController::class, 'destroy'])->middleware('permission:cms.activity.delete');

    // Benefit  AKA Manfaat
    Route::get('/benefits', [BenefitController::class, 'index'])->middleware('permission:cms.benefit.view');
    Route::post('/benefit', [BenefitController::class, 'store'])->middleware('permission:cms.benefit.create');
    Route::put('/benefit/{id}', [BenefitController::class, 'update'])->middleware('permission:cms.benefit.edit');
    Route::delete('/benefit/{id}', [BenefitController::class, 'destroy'])->middleware('permission:cms.benefit.delete');

    // News  AKA Berita
    Route::get('/news', [NewsController::class, 'index'])->middleware('permission:cms.news.view');
    Route::post('/news', [NewsController::class, 'store'])->middleware('permission:cms.news.create');
    Route::get('/news/{id}', [NewsController::class, 'show'])->middleware('permission:cms.news.show');
    Route::put('/news/{id}', [NewsController::class, 'update'])->middleware('permission:cms.news.edit');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->middleware('permission:cms.news.delete');

    // Departemen  AKA Department
    Route::get('/departments', [DepartemenController::class, 'index'])->middleware('permission:cms.departemen.view');
    Route::post('/department', [DepartemenController::class, 'store'])->middleware('permission:cms.departemen.create');
    Route::put('/department/{id}', [DepartemenController::class, 'update'])->middleware('permission:cms.departemen.edit');
    Route::delete('/department/{id}', [DepartemenController::class, 'destroy'])->middleware('permission:cms.departemen.delete');

    // FAQ  AKA Frequently Asked Questions
    Route::get('/faqs', [FaqController::class, 'index'])->middleware('permission:cms.faq.view');
    Route::post('/faq', [FaqController::class, 'store'])->middleware('permission:cms.faq.create');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->middleware('permission:cms.faq.edit');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->middleware('permission:cms.faq.delete');

    // Proker  AKA Program Kerja
    Route::get('/prokers', [ProkerController::class, 'index'])->middleware('permission:cms.proker.view');
    Route::post('/proker', [ProkerController::class, 'store'])->middleware('permission:cms.proker.create');
    Route::put('/proker/{id}', [ProkerController::class, 'update'])->middleware('permission:cms.proker.edit');
    Route::delete('/proker/{id}', [ProkerController::class, 'destroy'])->middleware('permission:cms.proker.delete');
});

// registration
Route::post('/registration', [RegistrationController::class, 'store']);

// Content Landing Page
Route::get('/content/activities', [ActivityController::class, 'landingIndex']);
Route::get('/content/benefits', [BenefitController::class, 'landingIndex']);
Route::get('/content/departments', [DepartemenController::class, 'landingIndex']);
Route::get('/content/prokers', [ProkerController::class, 'landingIndex']);
Route::get('/content/news', [NewsController::class, 'landingIndex']);
Route::get('/content/faqs', [FaqController::class, 'index']);


// Route::apiResource('activities', ActivityController::class);
// Route::apiResource('benefits', BenefitController::class);
// Route::apiResource('news', NewsController::class);
// Route::apiResource('departemens', DepartemenController::class);
// Route::apiResource('faqs', FaqController::class);
// Route::apiResource('prokers', ProkerController::class);
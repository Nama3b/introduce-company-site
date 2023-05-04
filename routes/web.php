<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('lohin', [LoginController::class, 'login']);
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/home', function () {
    return view('admin.home');
})->name('home')->middleware('auth');

Route::middleware(['auth:web'])->prefix('admin')->group(function () {
    Route::get('user/new', [UserController::class, 'new']);
    Route::post('user/profile/update', [UserController::class, 'updateProfile'])->name('profile-update');
    Route::get('user/change-password', [UserController::class, 'password'])->name('change-pass');
    Route::post('user/change-password', [UserController::class, 'changePassword'])->name('update-pass');

    Route::get('user', [UserCOntroller::class, 'list'])
        ->name('user')
        ->middleware(['checkManagerPermission:VIEW_USER']);
    Route::get('user/detail/{user}', [UserController::class, 'detail'])
        ->name('user.detail')
        ->middleware(['checkManagerPermission:VIEW_USER']);
    Route::get('user/store', [UserController::class, 'store'])
        ->name('user.store')
        ->middleware(['checkManagerPermission:CREAT_USER']);
    Route::get('user/edit/{user}', [UserController::class, 'edit'])
        ->name('user.edit')
        ->middleware(['checkManagerPermission:EDIT_USER']);
    Route::get('user/delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware(['checkManagerPermission:DELETE_USER']);

    Route::get('role', [RoleController::class, 'list'])
        ->name('role')
        ->middleware(['checkManagerPermission:VIEW_ROLE']);
    Route::get('role/detail/{role}', [RoleController::class, 'detail'])
        ->name('role.detail')
        ->middleware(['checkManagerPermission:VIEW_ROLE']);
    Route::post('role/store', [RoleController::class, 'store'])
        ->name('role.store')
        ->middleware(['checkManagerPermission:CREATE_ROLE']);
    Route::post('role/edit/{role}', [RoleController::class, 'edit'])
        ->name('role.edit')
        ->middleware(['checkManagerPermission:EDIT_ROLE']);
    Route::delete('role/delete/{role}', [RoleController::class, 'delete'])
        ->name('role.delete')
        ->middleware(['checkManagerPermission:DELETE_ROLE']);

    Route::get('permission', [PermissionController::class, 'list'])
        ->name('permission')
        ->middleware(['checkManagerPermission:VIEW_PERMISSION']);
    Route::get('permission/detail/{permission}', [PermissionController::class, 'detail'])
        ->name('permission.show')
        ->middleware(['checkManagerPermission:SHOW_PERMISSION']);
    Route::post('permission/store', [PermissionController::class, 'store'])
        ->name('permission.store')
        ->middleware(['checkManagerPermission:CREATE_PERMISSION']);
    Route::post('permission/edit/{permission}', [PermissionController::class, 'edit'])
        ->name('permission.edit')
        ->middleware(['checkManagerPermission:EDIT_PERMISSION']);
    Route::delete('permission/delete/{permission}', [PermissionController::class, 'delete'])
        ->name('permission.delete')
        ->middleware(['checkManagerPermission:DELETE_PERMISSION']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\MenuBuilderController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);

//Profile Routes
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
//Change Password Routes
Route::get('profile/changepassword', [ProfileController::class, 'changepass'])->name('profile.changepassindex');
Route::post('profile/changepassword', [ProfileController::class, 'changepassword'])->name('profile.changepassword');
Route::resource('pages', PageController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
//MenuController
Route::resource('menus', MenuController::class)->except(['show']);
Route::group(['as' => 'menus.', 'prefix' => 'menus/{id}/'], function () {
    Route::get('builder', [MenuBuilderController::class, 'index'])->name('builder');
});

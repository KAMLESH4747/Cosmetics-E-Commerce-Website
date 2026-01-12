<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Future Admin Routes (Placeholders)
    // Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    // Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
    // Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    // Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
});

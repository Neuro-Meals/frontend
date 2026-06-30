<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('landing');
});

// Static pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

Auth::routes();

// Role-based redirect after login
Route::get('/home', function () {
    if (auth()->user() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('home');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/deliveries', [AdminController::class, 'deliveries'])->name('deliveries');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/content', [AdminController::class, 'content'])->name('content');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// User routes
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

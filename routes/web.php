<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

Route::get('/', function () {
    return view('landing');
});

// Locale switching
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.switch');

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
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/meals', [AdminController::class, 'meals'])->name('meals');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/deliveries', [AdminController::class, 'deliveries'])->name('deliveries');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/content', [AdminController::class, 'content'])->name('content');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Reports - Phase 11
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'reportDashboard'])->name('dashboard');
        Route::get('/revenue', [AdminController::class, 'reportRevenue'])->name('revenue');
        Route::get('/delivery', [AdminController::class, 'reportDelivery'])->name('delivery');
        Route::get('/subscriptions', [AdminController::class, 'reportSubscriptions'])->name('subscriptions');
        Route::get('/notifications', [AdminController::class, 'reportNotifications'])->name('notifications');
        Route::get('/audit', [AdminController::class, 'reportAudit'])->name('audit');
    });
});

// User routes
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/subscriptions', [UserController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/meals', [UserController::class, 'meals'])->name('meals');
    Route::get('/nutrition', [UserController::class, 'nutrition'])->name('nutrition');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/delivery', [UserController::class, 'delivery'])->name('delivery');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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

// Test API connection
Route::get('/test', function () {
    return view('test-connection');
})->name('test.connection');

Route::get('/test-api', function () {
    $baseUrl = config('api.base_url');
    $start = microtime(true);

    try {
        $response = \Illuminate\Support\Facades\Http::withOptions([
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_CONNECTTIMEOUT => 10,
            ],
        ])->timeout(15)->get($baseUrl);

        $duration = round((microtime(true) - $start) * 1000, 2);
        return response()->json([
            'success' => true,
            'base_url' => $baseUrl,
            'status' => $response->status(),
            'duration_ms' => $duration,
            'body' => $response->body(),
        ]);
    } catch (\Exception $e) {
        $duration = round((microtime(true) - $start) * 1000, 2);
        return response()->json([
            'success' => false,
            'base_url' => $baseUrl,
            'status' => 500,
            'duration_ms' => $duration,
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('test.api');

// ─── Auth Routes (API-based) ───
// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Forgot Password
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification (OTP-based)
Route::get('verify-email', [VerificationController::class, 'show'])->name('verify.email');
Route::post('verify-email/verify', [VerificationController::class, 'verify'])->name('verify.email.verify');
Route::post('verify-email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Role-based redirect after login
Route::get('/home', function () {
    $authApi = app(\App\Services\Api\AuthApiService::class);
    if ($authApi->check() && $authApi->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('home');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('api.admin')->group(function () {
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
Route::prefix('user')->name('user.')->middleware('api.auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/subscriptions', [UserController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/meals', [UserController::class, 'meals'])->name('meals');
    Route::get('/nutrition', [UserController::class, 'nutrition'])->name('nutrition');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/delivery', [UserController::class, 'delivery'])->name('delivery');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
});

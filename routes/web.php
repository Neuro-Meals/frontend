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
use App\Http\Controllers\LandingController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

Route::get('/', [LandingController::class, 'index'])->name('landing');

// AI Chat placeholder endpoints
Route::post('/ai/chat/landing', [ChatController::class, 'landing'])->name('ai.chat.landing');
Route::middleware('auth')->post('/ai/chat/customer', [ChatController::class, 'customer'])->name('ai.chat.customer');

// Image uploads (handled locally by Laravel)
Route::middleware('api.auth')->group(function () {
    Route::post('/upload/image', [UploadController::class, 'uploadImage'])->name('upload.image');
    Route::post('/upload/images', [UploadController::class, 'uploadImages'])->name('upload.images');
    Route::post('/upload/avatar', [UploadController::class, 'uploadAvatar'])->name('upload.avatar');
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

// Test register by sending a real request to the API
Route::get('/test-register', function () {
    $authApi = app(\App\Services\Api\AuthApiService::class);
    $testEmail = 'test_' . time() . '@example.com';

    $response = $authApi->register([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $testEmail,
        'phone' => '+966551234567',
        'password' => 'password123',
        'location' => 'Riyadh',
        'address' => 'King Fahd Road',
        'gender' => 'male',
        'age' => 30,
        'height_cm' => 175,
        'weight_kg' => 70,
        'fitness_goal' => 'weight_loss',
        'dietary_preference' => 'standard',
        'allergies' => [],
    ]);

    return response()->json([
        'test_email' => $testEmail,
        'api_response' => $response,
    ]);
})->name('test.register');

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
Route::get('password/reset/{token?}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
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
    Route::get('/dashboard/live', [AdminController::class, 'dashboardLive'])->name('dashboard.live');
    Route::get('/live', [AdminController::class, 'live'])->name('live');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/customers/{id}/details', [AdminController::class, 'customerDetails'])->name('customers.details');
    Route::post('/customers/{id}/assign-plan', [AdminController::class, 'assignPlanToCustomer'])->name('customers.assign-plan');
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
    Route::post('/plans', [AdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{id}', [AdminController::class, 'showPlan'])->name('plans.show');
    Route::put('/plans/{id}', [AdminController::class, 'updatePlan'])->name('plans.update');
    Route::delete('/plans/{id}', [AdminController::class, 'destroyPlan'])->name('plans.destroy');
    Route::get('/meals', [AdminController::class, 'meals'])->name('meals');
    Route::post('/meals', [AdminController::class, 'storeMeal'])->name('meals.store');
    Route::get('/meals/{id}', [AdminController::class, 'showMeal'])->name('meals.show');
    Route::put('/meals/{id}', [AdminController::class, 'updateMeal'])->name('meals.update');
    Route::delete('/meals/{id}', [AdminController::class, 'destroyMeal'])->name('meals.destroy');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/deliveries', [AdminController::class, 'deliveries'])->name('deliveries');
    Route::post('/deliveries/{id}/assign-driver', [AdminController::class, 'assignDriver'])->name('deliveries.assign-driver');
    Route::post('/deliveries/{id}/update-status', [AdminController::class, 'updateDeliveryStatus'])->name('deliveries.update-status');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::post('/drivers', [AdminController::class, 'storeDriver'])->name('drivers.store');
    Route::put('/drivers/{id}', [AdminController::class, 'updateDriver'])->name('drivers.update');
    Route::delete('/drivers/{id}', [AdminController::class, 'destroyDriver'])->name('drivers.destroy');
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

// Payment / Checkout callbacks (no auth middleware - Stripe redirects without session)
Route::get('/payment-success', [UserController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-cancel', [UserController::class, 'paymentCancel'])->name('payment.cancel');

// User routes (customer only)
Route::prefix('user')->name('user.')->middleware(['api.auth', 'api.customer'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/subscriptions', [UserController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/subscriptions', [UserController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::post('/subscriptions/{subscriptionId}/pause', [UserController::class, 'pauseSubscription'])->name('subscriptions.pause');
    Route::post('/subscriptions/{subscriptionId}/resume', [UserController::class, 'resumeSubscription'])->name('subscriptions.resume');
    Route::get('/meals', [UserController::class, 'meals'])->name('meals');
    Route::get('/nutrition', [UserController::class, 'nutrition'])->name('nutrition');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::post('/orders/from-subscription', [UserController::class, 'createOrderFromSubscription'])->name('orders.from-subscription');
    Route::get('/delivery', [UserController::class, 'delivery'])->name('delivery');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
});

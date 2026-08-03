<?php

use App\Http\Controllers\User\PublicHealthProfileOptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.auth', 'api.customer'])
    ->get(
        '/user/health-profile-options',
        PublicHealthProfileOptionController::class
    )
    ->name('user.health-profile-options.public');

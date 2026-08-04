<?php

use App\Http\Controllers\Admin\HealthProfileOptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('api.admin')
    ->group(function () {
        Route::get('/health-profile-options', [HealthProfileOptionController::class, 'index'])
            ->name('health-profile-options.index');
        Route::post('/health-profile-options', [HealthProfileOptionController::class, 'store'])
            ->name('health-profile-options.store');
        Route::put('/health-profile-options/{optionId}', [HealthProfileOptionController::class, 'update'])
            ->name('health-profile-options.update');
        Route::patch('/health-profile-options/{optionId}/status', [HealthProfileOptionController::class, 'updateStatus'])
            ->name('health-profile-options.status');
        Route::delete('/health-profile-options/{optionId}', [HealthProfileOptionController::class, 'destroy'])
            ->name('health-profile-options.destroy');
    });
    
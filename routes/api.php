<?php

use App\Http\Controllers\{AuthController, LeadController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'api'     => 'operational',
    ]);
});

Route::prefix('auth')
    ->middleware('guest')
    ->group(function () {
        /**
         * Auth / Reset Password
         */
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    });

Route::prefix('/v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

        /**
        * Leads
        */
        Route::get('leads/', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/search', [LeadController::class, 'search'])->name('leads.search');
        Route::post('leads/', [LeadController::class, 'store'])->name('leads.store');
        Route::get('leads/{id}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('leads/{id}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');

        /**
        * Leads
        */
        Route::get('leads/', [LeadController::class, 'index'])->name('leads.index');
        Route::post('leads/', [LeadController::class, 'store'])->name('leads.store');
        Route::patch('leads/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
        Route::post('leads/{id}/convert', [LeadController::class, 'convertToOpportunity'])->name('leads.convert');
    });

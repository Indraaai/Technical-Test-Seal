<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OAuthController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\AdminLeaveRequestController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect']);
    Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // Employee routes
        Route::middleware('role:employee')->group(function () {
            Route::get('/leave-requests', [LeaveRequestController::class, 'index']);
            Route::post('/leave-requests', [LeaveRequestController::class, 'store']);
            Route::get('/leave-requests/{id}', [LeaveRequestController::class, 'show']);
        });
        // Admin routes
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/leave-requests', [AdminLeaveRequestController::class, 'index']);
            Route::get('/admin/leave-requests/{id}', [AdminLeaveRequestController::class, 'show']);
            Route::patch('/admin/leave-requests/{id}/approve', [AdminLeaveRequestController::class, 'approve']);
            Route::patch('/admin/leave-requests/{id}/reject', [AdminLeaveRequestController::class, 'reject']);
        });
    });
});

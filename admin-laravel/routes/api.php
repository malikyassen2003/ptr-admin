<?php
// FILE: routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\OrderController;

// ── Public ────────────────────────────────────────────────────────
Route::post('/admin/login', [AuthController::class, 'login']);

// ── Protected ─────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me',      [AuthController::class, 'me']);

    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Products
        Route::get('/products',         [ProductController::class, 'index']);
        Route::post('/products',        [ProductController::class, 'store']);
        Route::get('/products/{id}',    [ProductController::class, 'show']);
        Route::put('/products/{id}',    [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Categories
        Route::get('/categories',          [CategoryController::class, 'index']);
        Route::post('/categories',         [CategoryController::class, 'store']);
        Route::put('/categories/{id}',     [CategoryController::class, 'update']);
        Route::delete('/categories/{id}',  [CategoryController::class, 'destroy']);

        // Customers
        Route::get('/customers',          [CustomerController::class, 'index']);
        Route::delete('/customers/{id}',  [CustomerController::class, 'destroy']);

        // Orders
        Route::get('/orders',                    [OrderController::class, 'index']);
        Route::get('/orders/{id}',               [OrderController::class, 'show']);
        Route::patch('/orders/{id}/status',      [OrderController::class, 'updateStatus']);
    });
});
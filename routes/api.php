<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\FranchiseController;

Route::group([
    'prefix' => 'admin/auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:admin');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:admin');
});

// Public Routes (No Auth)
Route::post('franchises', [FranchiseController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('locations', [LocationController::class, 'index']);
Route::get('locations/{location}', [LocationController::class, 'show']);

// Admin Protected Routes
Route::middleware('auth:admin')->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('locations', LocationController::class)->except(['index', 'show']);
    Route::apiResource('franchises', FranchiseController::class)->except(['store']);
});

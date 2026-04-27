<?php

use App\Http\Controllers\Api\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin/auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:admin');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:admin');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:admin');
});

Route::middleware('auth:admin')->group(function () {
    Route::apiResource('products', \App\Http\Controllers\Api\Admin\ProductController::class);
    Route::apiResource('locations', \App\Http\Controllers\Api\Admin\LocationController::class);
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MillController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('orders', [MillController::class, 'orders']);
        Route::get('stock', [MillController::class, 'stock']);
        Route::get('issues', [MillController::class, 'issues']);
        Route::post('floor/spinning', [MillController::class, 'floor']);
    });
});

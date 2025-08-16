<?php

use App\Http\Controllers\Api\CutController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::resource('users', UserController::class);
    Route::resource('cuts', CutController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('orderProducts', OrderProductController::class);
    Route::resource('products', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::resource('reviews', ReviewController::class);
});

Route::group([], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::get('generate-invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
    Route::get('products/{productId}/reviews', [ReviewController::class, 'getReviewsByProduct']);
});

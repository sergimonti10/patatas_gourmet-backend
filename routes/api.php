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
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

Route::middleware([HandleCors::class, 'auth:sanctum'])->group(function () {
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


    // Route::get('/email/verify', function () {
    //     return response()->json(['message' => 'Por favor verifica tu email.'], 200);
    // })->name('verification.notice');

    // Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //     $request->fulfill();

    //     return response()->json(['message' => 'Email verificado correctamente.'], 200);
    // })->middleware(['signed'])->name('verification.verify');

    // Route::post('/email/resend', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();

    //     return response()->json(['message' => 'Link de envío de verificación'], 200);
    // })->middleware(['throttle:6,1'])->name('verification.resend');
});

Route::middleware([HandleCors::class])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::get('generate-invoice/{orderId}', [InvoiceController::class, 'generateInvoice']);
    Route::get('products/{productId}/reviews', [ReviewController::class, 'getReviewsByProduct']);
    Route::get('/health', fn() => response()->json(['status' => 'ok']));
    Route::get('/db-health', function () {
        DB::select('select 1');
        return response()->json(['db' => 'ok']);
    });
});

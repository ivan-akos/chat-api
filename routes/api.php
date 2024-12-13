<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes for handling auth
Route::group(['prefix' => 'auth'], function () {

    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    // email verification routes
    Route::group(['prefix' => 'email'], function () {
        Route::get('/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
        Route::get('/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
    });

    // routes that need auth to access
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});


// routes for handling contacts     
Route::group(['prefix' => 'contact', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/send-request/{contact_id}', [ContactController::class, 'sendRequest']);

});

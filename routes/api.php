<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserActivity;

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

Route::get('/users', [UserController::class, 'index'])->middleware(UserActivity::class); 

// routes for handling contacts     
Route::group(['prefix' => 'contacts', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [ContactController::class, 'showContacts']);
    Route::get('/requests/pending', [ContactController::class, 'showPending']);
    Route::post('/requests/{contact_id}', [ContactController::class, 'sendRequest']);       //maybe rename parameters?
    Route::post('/requests/{contact_id}/accept', [ContactController::class, 'acceptRequest']);
    Route::post('/requests/{contact_id}/deny', [ContactController::class, 'denyRequest']);
});

//routes for messages
Route::group(['prefix' => 'messages', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/from/{contact_id}', [MessageController::class, 'retrieveFrom']);
    Route::get('/to/{contact_id}', [MessageController::class, 'retrieveTo']);
    Route::post('/{contact_Id}', [MessageController::class, 'sendMessage']);
});

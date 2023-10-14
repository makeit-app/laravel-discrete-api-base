<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // register
    Route::middleware('throttle:12,1')->post('/register', 'RegisterController');
    // authenticate
    Route::middleware('throttle:6,1')->post('/auth', 'AuthenticateController');
    // password reset
    Route::middleware('guest')->prefix('password')->group(function () {
        // request password reset link
        Route::put('/forgot', 'PasswordForgotController')->name('password.request');
        // reset password
        Route::put('/reset', 'PasswordResetController');
    });
});
//
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//
Route::middleware('auth:sanctum')->group(function () {
    // logout
    Route::post('/logout', 'LogoutController');
    // do not forget to modify your User model to implement or not the MustVerifyEmail
    if (config('discreteapibase.features.email_verification', false) === true) {
        // email verification
        Route::prefix('email')->group(function () {
            // request verification link
            Route::middleware(['throttle:6,1'])->post('/verification-notification', 'VerificationResendController')->name('verification.send');
            // verify email
            Route::middleware(['signed'])->get('/verify/{id}/{hash}', 'VerificationController')->name('verification.verify');
        });
    }
    // user
    Route::prefix('/user')->group(function () {
        // get user
        Route::get('/', 'UserController');
        // delete user
        if (config('discreteapibase.features.user_delete', false) === true) {
            Route::delete('/', 'UserDeleteController');
        }
        // force-delete user (initiator must be super admin!)
        Route::delete('/force/{user_id}', 'UserForceDeleteController');
    });
});

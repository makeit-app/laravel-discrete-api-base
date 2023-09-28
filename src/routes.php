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
            // verify email
            Route::middleware(['signed'])->get('/verify/{id}/{hash}', 'VerificationController')->name('verification.verify');
            // request verification link
            Route::middleware(['throttle:6,1'])->post('/verification-notification', 'VerificationResendController')->name('verification.send');
        });
    }
    // user
    Route::middleware('auth:sanctum')->prefix('/user')->group(function () {
        // get user
        Route::get('/', 'UserController');
        //
        if (config('discreteapibase.features.user_delete', false) === true) {
            // delete user
            Route::delete('/', 'UserDeleteController');
        }
        // profile
        Route::prefix('/profile')->group(function () {
            // update
            Route::put('/', 'ProfileUpdateController');
            //
            if (config('discreteapibase.features.avatars', false) === true) {
                // avatar
                Route::prefix('/avatar')->group(function () {
                    // get as image
                    Route::get('/', 'ProfileAvatarController');
                    // upload new image
                    Route::post('/', 'ProfileAvatarUpdateController');
                    // remove image
                    Route::delete('/', 'ProfileAvatarDeleteController');
                });
            }
        });
    });
});
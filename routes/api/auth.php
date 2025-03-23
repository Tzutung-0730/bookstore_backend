<?php

use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('Register', [AuthController::class, 'Register']);
    Route::post('Login', [AuthController::class, 'Login']);
    Route::post('Logout', [AuthController::class, 'Logout']);
});
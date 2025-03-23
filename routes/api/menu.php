<?php

use App\Http\Controllers\MenuController;

Route::prefix('menu')->group(function () {
    Route::get('GetMenu', [MenuController::class, 'GetMenu']);
    Route::post('CreateMenu', [MenuController::class, 'CreateMenu']);
    Route::put('UpdateMenu/{id}', [MenuController::class, 'UpdateMenu']);
    Route::delete('DeleteMenu/{id}', [MenuController::class, 'DeleteMenu']);
});
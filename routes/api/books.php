<?php

use App\Controllers\BooksController;

Route::prefix('books')->group(function () {
    Route::get('GetBooks', [BooksController::class, 'GetBooks']);
    Route::post('GetBookByISBN/{isbn}', [BooksController::class, 'GetBookByISBN']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Controllers\WebScrapingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redis-test', function () {
    try {
        $result = Redis::ping();
        return "Redis Ping Response: " . $result;
    } catch (\Exception $e) {
        return "Redis 連線錯誤: " . $e->getMessage();
    }
});

// Route::get('/scrape-books', [WebScrapingController::class, 'ScrapeBooks']);
// Route::get('/stop-scraping', [WebScrapingController::class, 'StopScraping']);
// Route::get('/start-scraping', [WebScrapingController::class, 'StartScraping']);
// Route::get('/get-scrape-progress', [WebScrapingController::class, 'GetScrapeProgress']);

Route::get('/ScrapeBooks', [WebScrapingController::class, 'ScrapeBooks']);
Route::get('/StopScraping', [WebScrapingController::class, 'StopScraping']);
Route::get('/StartScraping', [WebScrapingController::class, 'StartScraping']);
Route::get('/GetScrapeProgress', [WebScrapingController::class, 'GetScrapeProgress']);
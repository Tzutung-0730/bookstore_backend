<?php

use Illuminate\Support\Facades\Route;

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
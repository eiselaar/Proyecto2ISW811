<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ScheduleController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/posts', [PostController::class, 'apiIndex']);
    Route::get('/queue', [PostController::class, 'apiQueue']);
    Route::get('/schedules', [ScheduleController::class, 'apiIndex']);
});
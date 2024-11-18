<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;

;

Route::middleware(['auth'])->group(function () {
    Route::get('/social/{provider}', [SocialController::class, 'connect']);
    Route::get('/social/{provider}/callback', [SocialController::class, 'callback']);
});

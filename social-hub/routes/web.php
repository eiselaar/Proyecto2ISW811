<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;

;

Route::middleware(['auth'])->group(function () {
    Route::get('/social/{provider}', [SocialController::class, 'connect']);
    Route::get('/social/{provider}/callback', [SocialController::class, 'callback']);
    
});

// routes/web.php
Route::post('notifications/{id}/mark-as-read', function($id) {
    auth()->user()->notifications->where('id', $id)->markAsRead();
    return response()->json(['success' => true]);
})->name('notifications.mark-read');

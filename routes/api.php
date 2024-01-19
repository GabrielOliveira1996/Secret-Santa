<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('secret-santa')->group(function () {  
    Route::post('/create-party', [App\Http\Controllers\ApiPartyController::class, 'create']);
    Route::get('/wishlist/{token}', [App\Http\Controllers\ApiPartyController::class, 'index']);
    Route::post('/wishlist/{token}/create', [App\Http\Controllers\ApiWishlistController::class, 'create']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-party', [App\Http\Controllers\ApiPartyController::class, 'create']);

Route::prefix('wishlist')->group(function () {  
    Route::get('/{token}', [App\Http\Controllers\ApiPartyController::class, 'index']);
    Route::post('/{token}/create', [App\Http\Controllers\ApiWishlistController::class, 'create']);
});
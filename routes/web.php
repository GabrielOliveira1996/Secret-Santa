<?php

use Illuminate\Support\Facades\Route;

Route::prefix('secret-santa')->group(function () {  
    Route::get('/', function () {
        return view('secret_santa.welcome');
    });
    Route::get('/wishlist/{token}', function(){
        return view('secret_santa.wishlist');
    });
});


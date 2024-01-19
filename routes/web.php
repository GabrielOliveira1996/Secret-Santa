<?php

use Illuminate\Support\Facades\Route;

Route::prefix('secret-santa')->group(function () {  
    Route::get('/', function () {
        return view('secret_santa.welcome');
    });
});


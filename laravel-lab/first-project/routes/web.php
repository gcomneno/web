<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home', [
        'greeting' => 'Hello',
        'showGreeting' => true,
    ]);
})->name('home');

Route::get('/about', function () {
    return response('about', 200);
})->name('about');

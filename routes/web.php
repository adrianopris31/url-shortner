<?php

use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::get('/{code}', ShortUrlController::class.'@index')->name('short-url');
Route::post('/shortify', ShortUrlController::class.'@store');

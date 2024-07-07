<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/preview-content', [ContentController::class, 'preview'])->name('content.preview');

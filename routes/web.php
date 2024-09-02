<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/iletisim', [HomeController::class, 'contact'])->name('contact');
Route::get('/{slug}', [ContentController::class, 'show'])->name('content.show');
Route::get('/language/{code}', [LanguageController::class, 'changeLanguage'])->name('language.change');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

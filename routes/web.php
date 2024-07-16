<?php

use App\Filament\Resources\ContentResource\Pages\CreateContent;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});


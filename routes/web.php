<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard'); 

Route::get('/sobre', [PageController::class, 'sobre'])->name('sobre');
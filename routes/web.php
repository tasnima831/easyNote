<?php

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\NotesController;
use App\Http\Controllers\Waitlist\WaitlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/notes', NotesController::class)->name('notes');

Route::post('/waitlist', [WaitlistController::class, 'store'])
    ->middleware('throttle:10,1')->name('waitlist.store');

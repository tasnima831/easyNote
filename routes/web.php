<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\NotesController;
use App\Http\Controllers\Waitlist\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'pages.auth.form', ['register' => false])->name('login');
    Route::view('/signup', 'pages.auth.form', ['register' => true])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::post('/signup', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/admin', function (Request $request) {
    abort_unless($request->user()->role === 'admin', 403);

    return view('pages.admin.index');
})->middleware('auth')->name('admin.dashboard');

Route::get('/', HomeController::class)->name('home');
Route::get('/notes', NotesController::class)->name('notes');

Route::post('/waitlist', [WaitlistController::class, 'store'])
    ->middleware('throttle:10,1')->name('waitlist.store');

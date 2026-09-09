<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/notes', 'notes')->name('notes');

Route::post('/waitlist', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'email', 'max:254'],
        'plan' => ['required', 'in:Plus,Studio'],
    ]);

    $entry = json_encode([...$validated, 'created_at' => now()->toIso8601String()], JSON_THROW_ON_ERROR).PHP_EOL;
    if (file_put_contents(storage_path('app/waitlist.jsonl'), $entry, FILE_APPEND | LOCK_EX) === false) {
        return response()->json(['message' => 'Could not save your interest. Please try again.'], 503);
    }

    return response()->json(['message' => 'You are on the list.'], 201);
})->middleware('throttle:10,1');

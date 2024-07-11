<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('events/my-events',  [EventController::class, 'myEvents']) -> name('my-events.all');
    Route::post('/events/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::resource('events', EventController::class);

    Route::resource('my-events', MyEventsController::class);
});

require __DIR__.'/auth.php';

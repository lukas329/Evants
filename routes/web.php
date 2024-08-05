<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventReviewController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
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
    Route::resource('events', EventController::class);
    Route::get('user/{id}', [UserController::class, 'detail']) ->name('users.detail');
    Route::resource('my-events', MyEventsController::class);
    Route::post('/messages', [ChatsController::class, 'fetchMessagesBetween']);
    Route::get('/chat/{id}', [ChatsController::class, 'index']);
    Route::get('/messages', [ChatsController::class, 'fetchMessages']);
    Route::post('/send-message', [ChatsController::class, 'sendMessage']);
    Route::get('/review/{id}', [ReviewController::class, 'index']);
    Route::get('/event-review/{id}', [EventReviewController::class, 'index']);
});



require __DIR__.'/auth.php';

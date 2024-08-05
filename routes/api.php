<?php

use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\EventReviewController;
use App\Http\Controllers\api\EventsController;
use App\Http\Controllers\api\MyEventsController;
use App\Http\Controllers\api\RegistrationController;
use App\Http\Controllers\api\ReviewController;
use App\Http\Controllers\api\StatsController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\SportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events/{id}', [EventsController::class, 'show']);
    Route::put('/events/{id}', [EventsController::class, 'update']);
    Route::get('/events', [EventsController::class, 'index']);
    Route::post('/events/{event}/join', [RegistrationController::class, 'join'])->name('events.join');
    Route::post('/events/{event}/cancel', [RegistrationController::class, 'cancelEvent']);
    Route::delete('events/{id}', [EventsController::class, 'destroy']);
    Route::get('/my-events', [MyEventsController::class, 'index']);
    Route::get('/sports', [SportController::class, 'index']);
    Route::get('registrations/{id}', [RegistrationController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'update']);
    Route::get('/myId', [UserController::class, 'getMyId'])->name('user.myId');
    Route::get('/chat/{id}', [ChatController::class, 'index']);
    Route::get('/stats', [StatsController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'submitReview']);
    Route::get('/user/reviews', [ReviewController::class, 'getUserReviews']);
    Route::get('/reviewData',[ReviewController::class, 'getReviewData']);

    Route::post('/event-reviews', [EventReviewController::class, 'submitReview']);
});

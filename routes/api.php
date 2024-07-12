<?php

use App\Http\Controllers\api\EventsController;
use App\Http\Controllers\api\MyEventsController;
use App\Http\Controllers\api\RegistrationController;
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
    Route::delete('events/{id}', [EventsController::class, 'destroy']);
    Route::get('/my-events', [MyEventsController::class, 'index']);
    Route::get('/sports', [SportController::class, 'index']);
    Route::get('registrations/{id}', [RegistrationController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'index']);
});

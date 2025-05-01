<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\UserController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('events')->middleware('throttle:api')->name('events.')->group(function () {
    Route::post('/create', [EventController::class, 'store'])->name('api.save');
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::put('/{id}', [EventController::class, 'update'])->name('api.update');
    Route::delete('/{id}', [EventController::class, 'delete'])->name('api.delete');
    Route::post('/event-booking', [UserController::class, 'eventBooking'])->name('api.booking.save');
    Route::get('/cancel-event-booking/{id}', [EventController::class, 'cancelEventTicket'])->name('api.booking.cancel');
});

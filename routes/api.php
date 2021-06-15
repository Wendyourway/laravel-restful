<?php

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */


use App\Http\Controllers\FareharborController;
use Illuminate\Support\Facades\Route;



Route::prefix('companies')->group(function () {
  Route::get('/', [FareharborController::class, 'index']);
  Route::prefix('{company}')->group(function () {
    Route::get('/', [FareharborController::class, 'getCompany']);
    Route::get('items', );
    Route::prefix('items')->group(function () {
      Route::get('/', [FareharborController::class, 'getItems']);
      Route::get('{itemId}/availabilities/{date}/', [FareharborController::class, 'getAvailableItems']);
    });
    Route::post('availabilities/{pk}/bookings/', [FareharborController::class, 'addNewBooking']);
    Route::get('bookings/{uuid}/', [FareharborController::class, 'getBooking']);
    Route::delete('bookings/{uuid}/', [FareharborController::class, 'deleteBooking']);
    Route::post('availabilities/{pk}/bookings/{uuid}/', [FareharborController::class, 'rebooking']);
  });
});

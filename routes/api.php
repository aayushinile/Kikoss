<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('forget-password', [UserController::class, 'forgetpassword']);
Route::post('verify-otp', [UserController::class, 'verifyotp']);
Route::post('change-password', [UserController::class, 'change_password']);
Route::get('home', [UserController::class, 'home']);
Route::post('tour-detail', [UserController::class, 'tour_detail']);
Route::get('virtual-tour-listing', [UserController::class, 'VirtualTourListing']);
Route::post('virtual-tour-detail', [UserController::class, 'VirtualTourDetail']);
Route::post('callback-request', [UserController::class, 'callback_request']);
Route::post('send-otp', [UserController::class, 'send_otp']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']); //Auth User Logout
    Route::get('profile', [UserController::class, 'userDetails']);
    Route::get('profiles', [UserController::class, 'userDetailss']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    Route::post('booking-tour', [UserController::class, 'bookingTour']);
    Route::post('booking-taxi', [UserController::class, 'bookingTaxi']);
    Route::get('taxi-booking-list', [UserController::class, 'TaxiBookingListing']);
    Route::get('photo-booth-listing', [UserController::class, 'PhotoBoothListing']);
    Route::post('photo-booth-details', [UserController::class, 'PhotoBoothDetails']);
    Route::post('purchased-photo-booth-listing', [UserController::class, 'PurchasedPhotoBoothListing']);
});
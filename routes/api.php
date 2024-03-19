<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentVenmoController;

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
Route::post('book-tour', [UserController::class, 'book_tour']);
Route::post('tour-detail', [UserController::class, 'tour_detail']);
Route::get('virtual-tour-listing', [UserController::class, 'VirtualTourListing']);
Route::post('virtual-tour-detail', [UserController::class, 'VirtualTourDetail']);
Route::get('virtual-tour-stop-listing', [UserController::class, 'VirtualTourStopsListing']);
Route::post('virtual-tour-stop-detail', [UserController::class, 'VirtualTourStopDetail']);
Route::post('callback-request', [UserController::class, 'callback_request']);
Route::post('send-otp', [UserController::class, 'send_otp']);
Route::get('calendarEvents', [UserController::class, 'calendarEvents']);
Route::get('TimeZoneList', [UserController::class, 'TimeZoneList']);
Route::get('legal-links', [UserController::class, 'legal_links']);
Route::post('booking-taxi', [UserController::class, 'bookingTaxi']);//with and without booking
Route::post('addTaxiBookingEvent', [UserController::class, 'addTaxiBookingEvent']);
Route::post('/pay', [PaymentController::class,'pay']);
Route::post('/payV', [PaymentVenmoController::class,'paywithVenmo']);
Route::get('/payment/success', [PaymentController::class,'success'])->name('success.payment');
Route::get('/payment/cancel', [PaymentController::class,'cancel'])->name('cancel.payment');
Route::post('/refund', [PaymentController::class,'refund'])->name('refund.payment');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']); //Auth User Logout
    Route::get('profile', [UserController::class, 'userDetails']);
    Route::get('profiles', [UserController::class, 'userDetailss']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    Route::post('booking-tour', [UserController::class, 'bookingTour']);
    Route::post('bookingPhotoBooth', [UserController::class, 'bookingPhotoBooth']);
    Route::get('taxi-booking-list', [UserController::class, 'TaxiBookingListing']);
    Route::post('taxi-booking-filter', [UserController::class, 'TaxiBookingFilter']);
    Route::get('photo-booth-listing', [UserController::class, 'PhotoBoothListing']);
    Route::post('photo-booth-details', [UserController::class, 'PhotoBoothDetails']);
    Route::post('purchased-photo-booth-listing', [UserController::class, 'PurchasedPhotoBoothListing']);
    Route::post('confirmed-tour', [UserController::class, 'confirmed_tour']);
    Route::post('confirmed-virtual-tour', [UserController::class, 'virtual_confirmed__tour']);
    Route::post('virtual-tour-booking', [UserController::class, 'virtual_tour_booking']);
    Route::post('confirmed-tour-detail', [UserController::class, 'confirmed_tour_detail']);
    Route::post('free-callback-request', [UserController::class, 'FreeCallbackRequest']);
    Route::post('free-callback-requests-data', [UserController::class, 'CallbackRequest']);
    Route::get('Photo-BoothPurchase-Listing', [UserController::class, 'PhotoBoothPurchaseListing']);
    Route::get('Photo-info', [UserController::class, 'PhotoBoothInfo']);
    Route::get('get-count', [UserController::class, 'getPurchasedTourCount']);
    Route::get('fetch-events', [UserController::class, 'fetchAvailability']);
    Route::post('add-photobooth', [UserController::class, 'addPhotoBooth']);
    Route::post('update-photobooth', [UserController::class, 'updatePhotoBooth']);
    Route::get('virtual-tour-filter', [UserController::class, 'VirtualTourFilter']);
    Route::get('photo-booth-filter', [UserController::class, 'PhotoBoothFilter']);
    Route::get('book-taxi-filter', [UserController::class, 'BookTaxiFilter']);
    Route::get('place-suggestions', [UserController::class, 'fetchPlaceSuggestions']);
    Route::get('get-notifications', [UserController::class, 'getNotification']);
    Route::post('clear-notifications', [UserController::class, 'ClearNotification']);
});
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('Users');
Route::get('/add-tour', [App\Http\Controllers\HomeController::class, 'AddTour'])->name('AddTour');
Route::get('/edit-tour/{id}', [App\Http\Controllers\HomeController::class, 'EditTour'])->name('EditTour');
Route::post('/SaveTour', [App\Http\Controllers\HomeController::class, 'SaveTour'])->name('SaveTour');
Route::get('/tours', [App\Http\Controllers\HomeController::class, 'tours'])->name('Tours');
Route::get('/user-details/{id}', [App\Http\Controllers\HomeController::class, 'userDetail'])->name('UserDetail');
Route::get('/manage-booking', [App\Http\Controllers\HomeController::class, 'ManageBooking'])->name('ManageBooking');
Route::get('/tour-inquiry-request', [App\Http\Controllers\HomeController::class, 'InquiryRequest'])->name('InquiryRequest');
Route::get('/view-transaction-history', [App\Http\Controllers\HomeController::class, 'ViewTransactionHistory'])->name('ViewTransactionHistory');
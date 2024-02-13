<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
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
Route::get('phpmyinfo', function () {
    phpinfo(); 
})->name('phpmyinfo');
Route::get('/send_otp', [App\Http\Controllers\MailController::class, 'send_otp']);
Route::get('/privacy-policy', [App\Http\Controllers\AjaxController::class, 'privacy_policy']);
Route::get('/about-us', [App\Http\Controllers\AjaxController::class, 'about_us']);
Route::get('/term-condition', [App\Http\Controllers\AjaxController::class, 'term_condition']);

Route::get('/privacy-policy', [App\Http\Controllers\AjaxController::class, 'privacy_policy']);
Route::get('/calendar', [App\Http\Controllers\AjaxController::class, 'index']);
Route::get('/calendar', [App\Http\Controllers\AjaxController::class, 'index']);
Route::get('/get-events', [App\Http\Controllers\AjaxController::class, 'getEvents']);
Route::post('/add-event', [App\Http\Controllers\AjaxController::class, 'addEvent']);
Route::get('/getTaxiBookingEvent', [App\Http\Controllers\AjaxController::class, 'getTaxiBookingEvent']);
Route::post('/addTaxiBookingEvent', [App\Http\Controllers\AjaxController::class, 'addTaxiBookingEvent']);
Route::get('/get-events-set2', [App\Http\Controllers\AjaxController::class, 'getEventsSet2']);

Auth::routes();
// Ajax route to toggle user status
Route::post("/toggleUserStatus", [App\Http\Controllers\AjaxController::class, 'toggleUserStatus'])->name('toggleUserStatus');
Route::post("/toggleRequestStatus", [App\Http\Controllers\AjaxController::class, 'toggleRequestStatus'])->name('toggleRequestStatus');

Route::post("/toggleTourStatus", [App\Http\Controllers\AjaxController::class, 'toggleTourStatus'])->name('toggleTourStatus');
Route::post("/toggleVirtualTourStatus", [App\Http\Controllers\AjaxController::class, 'toggleVirtualTourStatus'])->name('toggleVirtualTourStatus');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::match(['get', 'post'],'/users', [App\Http\Controllers\HomeController::class, 'users'])->name('Users');
Route::match(['get', 'post'],'/virtual-tour-archive', [App\Http\Controllers\HomeController::class, 'VirtualTourArchive'])->name('VirtualTourArchive');
Route::match(['get', 'post'],'/tour-archive', [App\Http\Controllers\HomeController::class, 'TourArchive'])->name('TourArchive');
Route::get('/add-tour', [App\Http\Controllers\HomeController::class, 'AddTour'])->name('AddTour');
Route::get('/edit-tour/{id}', [App\Http\Controllers\HomeController::class, 'EditTour'])->name('EditTour');
Route::post('/SaveTour', [App\Http\Controllers\HomeController::class, 'SaveTour'])->name('SaveTour');
Route::post('/update-Tour', [App\Http\Controllers\HomeController::class, 'UpdateTour'])->name('UpdateTour');
Route::match(['get', 'post'],'/tours', [App\Http\Controllers\HomeController::class, 'tours'])->name('Tours');
Route::get('/user-details/{id}', [App\Http\Controllers\HomeController::class, 'userDetail'])->name('UserDetail');
Route::match(['get', 'post'],'manage-booking', [App\Http\Controllers\HomeController::class, 'ManageBooking'])->name('ManageBooking');

Route::match(['get', 'post'],'payments', [App\Http\Controllers\HomeController::class, 'PaymentDetails'])->name('PaymentDetails');
Route::post('/get-date-graph', [App\Http\Controllers\AjaxController::class, 'filterByDate'])->name('filterByDate');

Route::post('/get-year-graph', [App\Http\Controllers\AjaxController::class, 'filterByYear'])->name('filterByYear');
Route::match(['get', 'post'],'/tour-callback-request', [App\Http\Controllers\HomeController::class, 'CallbackRequest'])->name('CallbackRequest');
Route::get('/view-transaction-history', [App\Http\Controllers\HomeController::class, 'ViewTransactionHistory'])->name('ViewTransactionHistory');
Route::get('/accept-tour-booking/{id}', [App\Http\Controllers\HomeController::class, 'AcceptTourBooking'])->name('AcceptTourBooking');
Route::get('/reject-tour-booking/{id}', [App\Http\Controllers\HomeController::class, 'RejectTourBooking'])->name('RejectTourBooking');
Route::post('/delete-tour', [App\Http\Controllers\HomeController::class, 'DeleteTour'])->name('DeleteTour');
Route::post('/archive-tour', [App\Http\Controllers\HomeController::class, 'ArchiveTour'])->name('ArchiveTour');
Route::match(['get', 'post'],'/manage-virtual-tour', [App\Http\Controllers\HomeController::class, 'ManageVirtualTour'])->name('ManageVirtualTour');
Route::get('/add-edit-virtual-tour', [App\Http\Controllers\HomeController::class, 'AddVirtualTour'])->name('AddVirtualTour');
Route::post('/submit-virtual-tour', [App\Http\Controllers\HomeController::class, 'SaveVirtualTour'])->name('SaveVirtualTour');
Route::post('/update-virtual-tour', [App\Http\Controllers\HomeController::class, 'UpdateVirtualTour'])->name('UpdateVirtualTour');
Route::get('/edit-virtual-tour/{id}', [App\Http\Controllers\HomeController::class, 'EditVirtualTour'])->name('EditVirtualTour');
Route::post('/delete-virtual-tour', [App\Http\Controllers\HomeController::class, 'DeleteVirtualTour'])->name('DeleteVirtualTour');
Route::post('/archive-virtual-tour', [App\Http\Controllers\HomeController::class, 'ArchiveVirtualTour'])->name('ArchiveVirtualTour');

Route::match(['get', 'post'],'/manage-photo-booth', [App\Http\Controllers\HomeController::class, 'ManagePhotoBooth'])->name('ManagePhotoBooth');
Route::get('/add-photo-booth', [App\Http\Controllers\HomeController::class, 'AddPhoto'])->name('AddPhoto');
Route::get('/edit-photo-booth/{id}', [App\Http\Controllers\HomeController::class, 'EditPhotoBooth'])->name('EditPhotoBooth');
Route::post('/delete-photo-booth', [App\Http\Controllers\HomeController::class, 'DeletePhotoBooth'])->name('DeletePhotoBooth');
Route::post('/submit-photo-booth', [App\Http\Controllers\HomeController::class, 'SavePhotoBooth'])->name('SavePhotoBooth');
Route::post('/update-photo-booth', [App\Http\Controllers\HomeController::class, 'UpdatePhotoBooth'])->name('UpdatePhotoBooth');



Route::match(['get', 'post'],'taxi-booking-request', [App\Http\Controllers\HomeController::class, 'TaxiBookingRequest'])->name('TaxiBookingRequest');
Route::get('/virtual-transaction-history', [App\Http\Controllers\HomeController::class, 'VirtualTransactionHistory'])->name('VirtualTransactionHistory');
Route::get('/photo-transaction-history', [App\Http\Controllers\HomeController::class, 'PhotoTransactionHistory'])->name('PhotoTransactionHistory');
Route::get('/load-sectors', [App\Http\Controllers\HomeController::class, 'loadSectors'])->name('load-sectors');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('Profile');
Route::post('/update-password', [App\Http\Controllers\HomeController::class, 'UpdatePassword'])->name('UpdatePassword');
Route::post('/update-profile', [App\Http\Controllers\HomeController::class, 'UpdateProfile'])->name('UpdateProfile');
Route::get('/tour-detail/{id}', [App\Http\Controllers\HomeController::class, 'TourDetails'])->name('TourDetails');
Route::get('/delete-booth-video-image/{id}', [App\Http\Controllers\HomeController::class, 'DeletePhotoBoothImage'])->name('DeletePhotoBoothImage');
Route::get('/delete-tour-image/{id}', [App\Http\Controllers\HomeController::class, 'DeleteTourImage'])->name('DeleteTourImage');
Route::get('/live_tours', [App\Http\Controllers\HomeController::class, 'live_tours'])->name('live_tours');
Route::get('/live_users', [App\Http\Controllers\HomeController::class, 'live_users'])->name('live_users');
Route::get('/live_callbacks', [App\Http\Controllers\HomeController::class, 'live_callbacks'])->name('live_callbacks');
Route::get('/search_name', [App\Http\Controllers\HomeController::class, 'search_name'])->name('search_name');
Route::get('/booked-dates',[App\Http\Controllers\HomeController::class, 'getBookedDates'])->name('booked-dates');
Route::get('/add-edit-master',[App\Http\Controllers\HomeController::class, 'AddEditMasterData'])->name('AddEditMasterData');
Route::get('/add-edit-setting',[App\Http\Controllers\HomeController::class, 'AddEditSettingData'])->name('AddEditSettingData');
Route::post('/update-setting',[App\Http\Controllers\HomeController::class, 'UpdateSettings'])->name('UpdateSettings');
Route::get('/DownloadWithWatermark',[App\Http\Controllers\HomeController::class, 'DownloadWithWatermark'])->name('DownloadWithWatermark');
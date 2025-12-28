<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HotelOwnerInvitationController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ExploreHotelController;
use App\Http\Controllers\imageTest\testImageControlle;
use App\Http\Controllers\OwnerHotel\HotelOwnerHotelController;
use App\Http\Controllers\OwnerHotel\HotelOwnerController;
use App\Http\Controllers\OwnerHotel\HotelOwnerReservationController;
use App\Http\Controllers\OwnerHotel\RoomControllerHotelOwner;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\RedeemPointController;
use App\Http\Controllers\User\UserAboutController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserContactController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminPropertyController;

use App\Http\Controllers\User\UserHotelController;
use App\Http\Controllers\User\UserPaymentController;
use App\Http\Controllers\User\UserReservationController;
use App\Http\Controllers\BankDetailController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\User\FavouriteHotelsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\User\DistrictHotelController;
use Illuminate\Support\Facades\Log;
//landing_Page
                //welcome page
Route::get('/', [WelcomeController::class, 'index'])->name('index');
                //About
Route::get('/about', [AboutController::class, 'viewAbout'])->name('about');
                //contact
Route::get('/contact', [ContactController::class, 'contactUs'])->name('contact');
                //Hotel all
Route::get('/explore/hotels', [ExploreHotelController::class, 'index'])->name('explore.hotels');
                //search Hotel part
Route::get('/explore/search/hotels{name}', [ExploreHotelController::class, 'search'])->name('explore.search.hotel');

//District Hotels
Route::get('/district/{id}',[DistrictHotelController::class,'showDistrictHotels'])->name('district.showDistrictHotels');

Route::post('/contact/send',[ContactController::class,'save'])->name('contact.send');
//admin

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard',[AdminController::class,'getAdminDashboard'])->name('admin.dashboard');

    //district
    Route::get('/district',[DistrictController::class,'getAdminDistrict'])->name('district.index');
    Route::get('/district/create',[DistrictController::class,'getAdminDistrictCreate'])->name('district.create');
    Route::post('/district/save',[DistrictController::class,'save'])->name('district.store');
    Route::get('/district/edit/{id}',[DistrictController::class,'edit'])->name('district.edit');
    Route::post('/district/edit/{id}',[DistrictController::class,'update'])->name('district.update');
    Route::delete('/district/{id}/edit',[DistrictController::class,'delete'])->name('district.delete');

    //Hotel
    Route::get('/hotel',[HotelController::class,'index'])->name('hotel.index');
    Route::get('/hotel/create',[HotelController::class,'create'])->name('hotel.create');
    Route::post('/hotel/store',[HotelController::class,'store'])->name('hotel.store');
    Route::get('/hotel/edit/{id}',[HotelController::class,'edit'])->name('hotel.edit');
    Route::post('/hotel/edit/{id}',[HotelController::class,'update'])->name('hotel.update');
    Route::delete('/hotel/{id}/delete',[HotelController::class,'delete'])->name('hotel.delete');
    Route::get('/hotel/{id}/show',[HotelController::class,'show'])->name('hotel.show');

    //hotel super admin
    Route::get('/invite-hotel-owner',[HotelOwnerInvitationController::class,'index'])->name('invite-hotel-owner.index');
    Route::post('/update-permit-status/edit/{id}',[HotelOwnerInvitationController::class,'update'])->name('update-permit-status.update');

    //reservations
    Route::post('/reservation/store',[ReservationController::class,'store'])->name('reservations.store');

    //imageTest my test part
    Route::get('/imageTest/create',[testImageControlle::class,'create'])->name('imageTest.create');
    Route::post('/imageTest/store',[testImageControlle::class,'store'])->name('imageTest.store');
    Route::get('/imageTest/index',[testImageControlle::class,'index'])->name('imageTest.index');
    Route::get('/imageTest/edit/{id}',[testImageControlle::class,'edit'])->name('imageTest.edit');
    Route::post('/imageTest/update/{id}',[testImageControlle::class,'update'])->name('imageTest.update');

    //property requests
    Route::get('/property-request',[AdminPropertyController::class,'index'])->name('admin.propertyRequest');
    Route::get('/property-request-view/{id}', [AdminPropertyController::class, 'show'])->name('admin.propertyReqView');
});

//user
Route::middleware(['auth','role:user'])->group(function () {
    Route::get('/user-register-hotel-owner', [RegisteredUserController::class, 'createHotelOwner'])->name('user-register-hotel-owner');
    Route::post('register/hotel-owner', [RegisteredUserController::class, 'storeHotelOwner'])->name('hotel-owner.register');

    Route::get('/user/dashboard',[UserController::class,'userDashboard'])->name('user.dashboard');
    Route::get('/user/about', [UserAboutController::class, 'index'])->name('user.about');
    Route::get('/user/contact', [UserContactController::class, 'index'])->name('user.contact');

    //Hotels
    Route::get('/user/hotel',[UserHotelController::class,'index'])->name('user.hotel.index');
    Route::get('/user/view-hotel/{id}',[UserHotelController::class,'show'])->name('view-hotel.show');
    Route::get('/user/hotel/show/{id}',[UserHotelController::class,'show'])->name('user.hotel.show');
    Route::get('user/hotel_find_by_state/{name}',[UserHotelController::class,'findByState'])->name('user.hotel.findByState');
    // Submit rating for a hotel
    Route::post('/hotels/{id}/rating', [UserHotelController::class, 'rating']);
    //favorites
    Route::get('/favourite-hotels',[FavouriteHotelsController::class,'index'])->name('favorites.index');
    Route::post('/favorites/store',[FavouriteHotelsController::class,'store'])->name('favorites.store');
    Route::delete('/favorites/{id}/delete', [FavouriteHotelsController::class, 'delete'])->name('favorites.delete');
    Route::delete('/favorites/delete-multiple', [FavouriteHotelsController::class, 'deleteMultiple'])->name('favorites.deleteMultiple');

    //reservation
            //get redeem part
    Route::get('/user/reservations', [UserReservationController::class, 'index'])->name('user.reservations.index');
    Route::post('/user/reservations/store', [UserReservationController::class, 'store'])->name('user.reservations.store');
    Route::get('/user/reservations/{id}', [UserReservationController::class, 'show'])->name('user.reservations.show');
    Route::post('/user/reservations/cancel/{id}', [UserReservationController::class, 'cancel'])->name('user.reservations.cancel');
    Route::delete('/user/reservations/{id}/delete',[UserReservationController::class,'delete'])->name('user.reservations.delete');

//    Route::get('/user/reservations/payment/{id}', [UserReservationController::class, 'showPayment'])->name('user.reservations.payment');
    Route::get('/user/reservations/pay/{id}', [UserReservationController::class, 'pay'])->name('user.reservations.payment');

    //Booking
    Route::post('/booking/store',[UserBookingController::class,'store'])->name('booking.store');
    Route::post('/payment/initiate',[UserPaymentController::class,'initiate'])->name('payment.initiate');
    Route::get('/payment/verify',[UserPaymentController::class,'verify'])->name('payment.verify');

//offer part
    //redeem update part
    Route::post('/points/redeem', [RedeemPointController::class, 'redeem'])->name('points.redeem');
    //get part
    Route::get('/points/redeem/get', [RedeemPointController::class, 'getRedeem'])->name('points.redeem.get');
});

//hotel owner
Route::middleware(['auth','role:hotel_owner'])->group(function () {
    Route::get('/hotelOwner/dashboard',[HotelOwnerController::class,'authUser'])->name('hotelOwner.dashboard');
    Route::post('/my-hotel/reservations/update/{id}', [HotelOwnerController::class, 'update'])->name('hotelOwner.reservations.update');

    //Hotel
    Route::get('/my-hotels',[HotelOwnerHotelController::class,'index'])->name('hotelOwner.hotel.index');
    Route::get('/my-hotels/create',[HotelOwnerHotelController::class,'create'])->name('hotelOwner.hotel.create');
    Route::post('/my-hotels/store',[HotelOwnerHotelController::class,'store'])->name('hotelOwner.hotel.store');
    Route::get('/my-hotels/edit/{id}',[HotelOwnerHotelController::class,'edit'])->name('hotelOwner.hotel.edit');
    Route::post('/my-hotels/edit/{id}',[HotelOwnerHotelController::class,'update'])->name('hotelOwner.hotel.update');
    Route::delete('/my-hotels/{id}/delete',[HotelOwnerHotelController::class,'delete'])->name('hotelOwner.hotel.delete');
    Route::get('/my-hotels/show/{id}',[HotelOwnerHotelController::class,'show'])->name('hotelOwner.hotel.show');

    //Room
    Route::get('/my-room/{id}',[RoomControllerHotelOwner::class,'index'])->name('hotelOwner.room.index');
    Route::get('/my-room/create/{id}',[RoomControllerHotelOwner::class,'create'])->name('hotelOwner.room.create');
    Route::post('/my-room/store',[RoomControllerHotelOwner::class,'store'])->name('hotelOwner.room.store');
    Route::get('/my-room/edit/{id}',[RoomControllerHotelOwner::class,'edit'])->name('hotelOwner.room.edit');
    Route::post('/my-room/edit/{id}',[RoomControllerHotelOwner::class,'update'])->name('hotelOwner.room.update');
    Route::delete('/my-room/{id}/delete',[RoomControllerHotelOwner::class,'delete'])->name('hotelOwner.room.delete');


    Route::get('/my-hotel/reservations', [HotelOwnerReservationController::class, 'index'])->name('hotelOwner.reservations.index');
    //reservation Save
    Route::post('/my-hotel/reservations/update/{id}', [HotelOwnerReservationController::class, 'update'])->name('hotelOwner.reservations.update');

//    Route::get('/user/reservations/{id}', [UserReservationController::class, 'show'])->name('user.reservations.show');
//            //reservation Update
//    Route::post('/user/reservations/cancel/{id}', [UserReservationController::class, 'cancel'])->name('user.reservations.cancel');
//            //reservation Delete
//    Route::delete('/user/reservations/{id}/delete',[UserReservationController::class,'delete'])->name('user.reservations.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Endpoint to process expired reservations immediately (used by frontend before reload)
Route::post('/reservations/process-expired', [\App\Http\Controllers\ReservationExpiryController::class, 'process'])
    ->name('reservations.processExpired')
    ->middleware('auth');

require __DIR__.'/auth.php';

//need to remove this comment
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//Route::get('/new', [tourNew::class, 'get'])->middleware(['auth', 'verified'])->name('new');
//    Route::get('/invite-hotel-owner-create',[HotelOwnerInvitationController::class,'create'])->name('invite-hotel-owner-create.create');
//    Route::post('/invite-hotel-owner-create',[HotelOwnerInvitationController::class,'store'])->name('invite-hotel-owner-create.store');
//    Route::delete('/invite-hotel-owner/delete/{id}',[HotelOwnerInvitationController::class,'delete'])->name('invite-hotel-owner.destroy');
//
Route::resource('bank-detail', BankDetailController::class);

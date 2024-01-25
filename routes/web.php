<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBaseBackupController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportCategoryController;
use App\Http\Controllers\ReportCustomerController;
use App\Http\Controllers\ReportProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Livewire\OnlineStatus;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// Authentication Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/',[LoginController::class,'index'])->name('login');
    Route::get('/login',[LoginController::class,'index'])->name('login');
    Route::post('/login',[LoginController::class,'login'])->name('authenticate');
    Route::get('/forgot-password',[ForgotPasswordController::class,'index'])->name('forgot.password');
    Route::post('/forgot-pass-mail',[ForgotPasswordController::class,'sendResetLinkEmail'])->name('password_mail_link');
    Route::get('reset-password/{token}/{email}', [ResetPasswordController::class,'showform'])->name('resetPassword');
    Route::post('/reset-password',[ResetPasswordController::class,'resetpass'])->name('reset-new-password');

});

Route::middleware(['auth','PreventBackHistory'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    Route::resource('/roles',RoleController::class);
    Route::get('/profiles',[UserController::class,'showprofile'])->name('user.profile');
    Route::post('/profile-update', [UserController::class,'updateprofile'])->name('profile.update');
    Route::post('/profile-image', [UserController::class,'updateprofileImage'])->name('profile-image.update');
    Route::get('/change-password',[UserController::class,'showchangepassform'])->name('user.change-password');
    Route::post('/change-password',[UserController::class,'updatePassword'])->name('reset-password');
    Route::resource('/address',AddressController::class);
    Route::get('/address-printView/{address_id?}',[AddressController::class,'printView'])->name('address.print');
    Route::get('/address-export/{address_id?}',[AddressController::class,'export'])->name('address.export');

    Route::resource('/staff',UserController::class);
    Route::get('/staff/password/{id}',[UserController::class,'staffpassword'])->name('staff.password');
    Route::put('/staff/password/{id}',[UserController::class,'staffUpdatePass'])->name('staff.change-password');
    Route::get('/staff-printView',[UserController::class,'printView'])->name('staff.print');
    Route::get('staff-export/',[UserController::class,'export'])->name('staff.export');

    Route::get('/staff/typeindex/{type?}',[UserController::class,'typeindex'])->name('staff.typeindex');
    Route::patch('/staff/{staff}/rejoin',[UserController::class,'rejoin'])->name('staff.rejoin');

    Route::get('/settings/{tab?}',[SettingController::class,'index'])->name('settings');
    Route::post('/settings/update',[SettingController::class,'update'])->name('settings.update');

});


Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/refresh', function () {
    // Run Artisan commands
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'App refreshed successfully!';
});

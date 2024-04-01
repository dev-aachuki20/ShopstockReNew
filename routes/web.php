<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleIpController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\Master\CategoriesController;
use App\Http\Controllers\Admin\Master\AreaController;
use App\Http\Controllers\Admin\Master\GroupController;
use App\Http\Controllers\Admin\Master\ProductController;
use App\Http\Controllers\Admin\Master\LogActivitiesController;
use App\Http\Controllers\Admin\Master\SplitsController;
use App\Http\Controllers\Admin\Master\ProductUnitController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentTransactionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// Authentication Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('authenticate');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot.password');
    Route::post('/forgot-pass-mail', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password_mail_link');
    Route::get('reset-password/{token}/{email}', [ResetPasswordController::class, 'showform'])->name('resetPassword');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetpass'])->name('reset-new-password');
});


Route::group(['middleware' => ['auth', 'PreventBackHistory'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        // Route::resource('/categories', CategoriesController::class);
        Route::resource('/areas', AreaController::class);
        Route::resource('/groups', GroupController::class);
        Route::get('/group-recycle', [GroupController::class, 'recycleIndex'])->name('groups.recycle');
        Route::post('/group-undo', [GroupController::class, 'undoGroup'])->name('groups.undo');
        Route::get('/group-parent', [GroupController::class, 'getGroupParent'])->name('get_group_parent');
        Route::get('/group-child', [GroupController::class, 'getSubGroup'])->name('get_group_child');
        Route::get('group-export/', [GroupController::class, 'export'])->name('group.export');

        // sub groups
        Route::get('/sub-groups/index', [GroupController::class, 'subGroupIndex'])->name('sub_group.index');
        Route::get('/sub-group-recycle', [GroupController::class, 'subGroupRecycleIndex'])->name('sub.group.recycle');
        Route::get('sub-group-export/', [GroupController::class, 'exportSubGroup'])->name('sub.group.export');
        // sub groups

        Route::resource('/products', ProductController::class);
        Route::get('product-export/', [ProductController::class, 'export'])->name('product.export');
        Route::get('/product-recycle', [ProductController::class, 'recycleIndex'])->name('product.recycle');
        Route::post('/product-undo', [ProductController::class, 'undoGroup'])->name('product.undo');


        Route::get('/product-price/update-prices', [ProductController::class, 'viewUpdateProductPrice'])->name('update-prices');
        Route::get('/product-price/product-price-list', [ProductController::class, 'productPriceList'])->name('product-price-list');
        Route::post('/product-price/product-price-udpate', [ProductController::class, 'updateProductPrice'])->name('updateProductPrice');
        Route::post('/product-price/group-product-price-udpate', [ProductController::class, 'updateProductPriceGroup'])->name('updateProductPriceGroup');

        Route::get('/product-group/update', [ProductController::class, 'viewUpdateProductGroup'])->name('update-product-group');
        Route::get('/product-group/product-group-list', [ProductController::class, 'productUpdateGroupList'])->name('product-group-list');
        Route::post('/product-group/product-group-udpate', [ProductController::class, 'updateProductGroup'])->name('product-group-update');

        Route::resource('/log-activity', LogActivitiesController::class);
        Route::resource('/product-unit', ProductUnitController::class);
        //Route::resource('/split', SplitsController::class);
        Route::resource('/role_ip', RoleIpController::class);
    });

    Route::get('/get_product_add_form', [ProductController::class, 'create'])->name('get_product_add_form');

    Route::resource('/customers', CustomerController::class);
    Route::get('/customer/list', [CustomerController::class, 'customerList'])->name('customer_list');
    Route::get('/customer/view-customer', [CustomerController::class, 'viewCostomer'])->name('customers.view_customer');
    Route::post('/customer/history-filter', [CustomerController::class, 'historyFilter'])->name('customers.historyFilter');
    Route::post('/customer/name-list', [CustomerController::class, 'getCustomerNameList'])->name('customers.namelist');

    Route::get('orders-return', [OrdersController::class, 'returnCreate'])->name('orders.return');
    Route::get('orders/draft-invoice',[OrdersController::class, 'draftInvoice'])->name('orders.draftInvoice');
    Route::get('orders/{type?}/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::get('orders/{type?}/{id}', [OrdersController::class, 'showHistory'])->name('orders.history.show');
    Route::resource('/orders', OrdersController::class)->except('edit');

    Route::get('/get_customer_detail', [OrdersController::class, 'get_customer_detail'])->name('customer_detail');
    Route::post('/get_product_detail', [OrdersController::class, 'get_product_detail'])->name('get_product_detail');
    Route::post('/add_product_row', [OrdersController::class, 'add_product_row'])->name('add_product_row');
    Route::post('orders/edit-product', [OrdersController::class, 'EditProduct'])->name('orders.editProduct');

    Route::get('order/print-pdf/{orderid}',[OrdersController::class, 'printPdf'])->name('order.printPdf');

    Route::resource('/transactions', PaymentTransactionsController::class);
    Route::get('transaction/{type}', [PaymentTransactionsController::class, 'typeFilter'])->name('transactions.type');
    Route::get('transactions/{type?}/{id}', [PaymentTransactionsController::class, 'showHistory'])->name('transactions.history.show');


    Route::post('checkInvoiceNumber', [OrdersController::class, 'checkInvoiceNumber'])->name('orders.checkInvoiceNumber');
    Route::post('/add-glass-product-view', [OrdersController::class, 'addGlassProductView'])->name('addGlassProductView');
});


Route::middleware(['auth', 'PreventBackHistory'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('/roles', RoleController::class);


    Route::post('/user-status-change', [UserController::class, 'userStatusChange'])->name('user_status_change');
    Route::get('/profiles', [UserController::class, 'showprofile'])->name('user.profile');
    Route::post('/profile-update', [UserController::class, 'updateprofile'])->name('profile.update');
    Route::post('/profile-image', [UserController::class, 'updateprofileImage'])->name('profile-image.update');
    Route::get('/change-password', [UserController::class, 'showchangepassform'])->name('user.change-password');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('reset-password');
    Route::resource('/address', AddressController::class);
    Route::get('/address-printView/{address_id?}', [AddressController::class, 'printView'])->name('address.print');
    Route::get('/address-export/{address_id?}', [AddressController::class, 'export'])->name('address.export');

    Route::resource('/staff', UserController::class);
    Route::get('/staff/password/{id}', [UserController::class, 'staffpassword'])->name('staff.password');
    Route::put('/staff/password/{id}', [UserController::class, 'staffUpdatePass'])->name('staff.change-password');
    Route::get('/staff-printView', [UserController::class, 'printView'])->name('staff.print');
    Route::get('staff-export/', [UserController::class, 'export'])->name('staff.export');

    Route::get('/staff/typeindex/{type?}', [UserController::class, 'typeindex'])->name('staff.typeindex');
    Route::patch('/staff/{staff}/rejoin', [UserController::class, 'rejoin'])->name('staff.rejoin');

    Route::get('/settings/{tab?}', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
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

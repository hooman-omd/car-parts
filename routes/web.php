<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\main\CartController;
use App\Http\Controllers\main\IndexController;
use App\Http\Controllers\main\UserDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\AdminAuthorize;
use App\Http\Middleware\Authorize;
use App\Http\Middleware\NonAuthorize;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('details/{product_id}', [IndexController::class, 'details'])->name('product.details');
Route::get('/products', [IndexController::class, 'productFilters'])->name('product.productFilters');
Route::get('/getBasket', [CartController::class, 'getCart'])->name('basket.get');
Route::get('/addToBasket', [CartController::class, 'addToCart'])->name('basket.add');
Route::get('/removeFromBasket', [CartController::class, 'removeFromCart'])->name('basket.remove');
Route::get('/cart', [IndexController::class, 'cart'])->name('basket.cart');
Route::get('/search-products', [ProductsController::class, 'search'])->name('search.products');
Route::post('/logout', [AuthController::class, 'logout'])->middleware([Authorize::class,'prevent-back'])->name('auth.logout');
Route::get('/logout', fn() => abort('404'));

Route::prefix('payment')->middleware(Authorize::class)->group(function(){
    Route::post('/complete-payment/{orderObj?}',[PaymentController::class,'store'])->name('basket.complete-payment');
    Route::get('/complete-payment', fn() => abort('404'));
    Route::get('/payment-success/{payment_id}',[PaymentController::class,''])->name('payment.success');
    Route::post('/discount-code',[PaymentController::class,'setDiscountCode'])->name('basket.setDiscountCode');
    Route::get('verify/{order_id}',[PaymentController::class,'verifyPayment'])->name('payment.verify');
    Route::delete('/delete-order/{order}',[PaymentController::class,'deleteUserOrder'])->can('delete','order')->name('basket.delete-order');
});

Route::prefix('auth')->middleware(NonAuthorize::class)->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('auth.form.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.form.register');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('auth.register');
    Route::get('/set-email', [AuthController::class, 'resestPassword'])->name('auth.resest-password.set-email');
    Route::post('/set-email', [AuthController::class, 'setEmail'])->name('auth.set-email');
    Route::get()
});

/////////// user dashboard /////////////
Route::prefix('dashboard')->middleware([Authorize::class,'prevent-back'])->group(function () {
    Route::get('/addresses', [UserDashboardController::class, 'addresses'])->name('userdashboard.addresses');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('userdashboard.orders');
    Route::get('/orders/{order_id}', [UserDashboardController::class, 'orderDetail'])->name('userdashboard.orderDetail');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('userdashboard.profile');
    Route::patch('/profile', [AuthController::class, 'editProfile'])->name('userdashboard.profile.edit');
    Route::get('/get-cities', [UserDashboardController::class, 'getCities'])->name('userdashboard.get-cities');
    Route::post('/add-new-address', [UserDashboardController::class, 'addAddress'])->name('userdashboard.addAddress');
    Route::get('/add-new-address', fn() => abort('404'));
    Route::patch('/edit-address', [UserDashboardController::class, 'editAddress'])->can('update','userAddress')->name('userdashboard.editAddress');
    Route::get('/edit-address', fn() => abort('404'));
    Route::post('/set-defualt', [UserDashboardController::class, 'setDefualt'])->name('userdashboard.setDefualt');
    Route::get('/set-defualt', fn() => abort('404'));
    Route::delete('/delete-address', [UserDashboardController::class, 'deleteAddressDashboard'])->can('delete','userAddress')->name('userdashboard.deleteAddressDashboard');
    Route::get('/delete-address', fn() => abort('404'));
});


Route::prefix('panel')->middleware([AdminAuthorize::class,'prevent-back'])->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoriesController::class, 'index'])->name('categories.index');
        Route::post('/', [CategoriesController::class, 'store'])->name('categories.store');

        Route::delete('/{category_id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
        Route::get('/{category_id}', [CategoriesController::class, 'edit'])->name('categories.edit');
        Route::patch('/{category_id}', [CategoriesController::class, 'update'])->name('categories.update');
    });

    Route::prefix('products')->group(function () {
        Route::get('/create', [ProductsController::class, 'create'])->name('products.create');
        Route::post('/create', [ProductsController::class, 'store'])->name('products.store');
        Route::get('/', [ProductsController::class, 'index'])->name('products.index');
        Route::get('/{productId}', [ProductsController::class, 'edit'])->name('products.edit');
        Route::patch('/{productId}', [ProductsController::class, 'update'])->name('products.update');
        Route::get('image/{productId}/{thumbnailId}', [ProductsController::class, 'deleteImage'])->name('products.deleteImage');
        Route::delete('/{productId}', [ProductsController::class, 'destroy'])->name('products.destroy');
        Route::get('/increase-inventory/{productId}', [ProductsController::class, 'increaseInventory'])->name('products.increaseInventory');
        Route::get('/discount/{productId}', [ProductsController::class, 'discount'])->name('products.discount');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::patch('/edit-user', [UsersController::class, 'editUser'])->name('users.editUser');
        Route::get('/edit-user', fn() => abort('404'));
        Route::delete('/delete-user', [UsersController::class, 'deleteUser'])->name('users.deleteUser');
        Route::get('/delete-user', fn() => abort('404'));
        Route::get('/addresse/{user_id}', [UsersController::class, 'getAddresses'])->name('users.getAddresses');
        Route::delete('/delete-address', [UserDashboardController::class, 'deleteAddressPanel'])->name('userdashboard.deleteAddressPanel');
        Route::get('/delete-address', fn() => abort('404'));
        Route::patch('/edit-address', [UserDashboardController::class, 'editAddressPanel'])->name('userdashboard.editAddressPanel');
        Route::get('/edit-address', fn() => abort('404'));
    });

    Route::prefix('codes')->group(function (){
        Route::get('/', [DiscountCodeController::class, 'index'])->name('codes.index');
        Route::post('/', [DiscountCodeController::class, 'store'])->name('codes.store');
        Route::patch('/', [DiscountCodeController::class, 'edit'])->name('codes.edit');
        Route::delete('/', [DiscountCodeController::class, 'delete'])->name('codes.destroy');
        Route::get('/add-to', [DiscountCodeController::class, 'addTo'])->name('codes.addTo');
    });

    Route::prefix('orders')->group(function(){
        Route::get('/',[PaymentController::class,'getOrders'])->name('orders.get');
        Route::delete('/',[PaymentController::class,'deleteOrder'])->name('orders.delete');
    });
});

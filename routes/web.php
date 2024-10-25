<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomeMiration;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cc', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('custome-miration', [CustomeMiration::class, 'index'])->name('custome-miration');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* Products */
Route::get('products',[ProductController::class,'index'])->name('products');
Route::get('product/cart',[ProductController::class,'product_cart'])->name('product.cart');
Route::post('product/add_to_cart',[ProductController::class,'add_to_cart'])->name('product.add-to-cart');
Route::post('product/remove_product_from_cart',[ProductController::class,'remove_from_cart'])->name('product.remove-product-from-cart');

Route::get('product/checkout',[ProductController::class,'checkout'])->name('product.checkout');

Route::post('product/create_order_charge',[ProductController::class,'create_order_charge'])->name('product.create_order_charge');
Route::post('product/processing-order-booking',[ProductController::class,'processingOrderBooking'])->name('product.processing-order-booking');
Route::get('product/order/success-booking',[ProductController::class,'success_booking'])->name('product.order.success_booking');




/**
 * All Admin route added in this file
 */
include('admin.php');

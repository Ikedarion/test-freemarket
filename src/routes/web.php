<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterUserController;

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

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'showDetail'])->name('product.show');
Route::post('/products/comment', [ProductController::class, 'storeComment'])->name('comment.store');
Route::get('/sell', [ProductController::class, 'create'])->name('product.create');
Route::post('/products/sell', [ProductController::class, 'store'])->name('product.store');
Route::get('/purchase/{id}', [ProductController::class, 'showPurchaseForm'])->name('purchase');
Route::post('/likes/{id}', [ProductController::class, 'like'])->name('like');

Route::get('/mypage', [UserController::class, 'index'])->name('my-page');
Route::get('/mypage/profile', [UserController::class, 'create'])->name('profile.create');
Route::post('/mypage/profile/{id}', [UserController::class, 'store'])->name('profile.store');
Route::patch('/mypage/profile/update/{id}', [UserController::class, 'update'])->name('profile.update');
Route::get('/purchase/address/{id}', [UserController::class, 'edit'])->name('address');
Route::patch('/purchase/address/update/{id}', [UserController::class, 'updateAddress'])->name('address.update');

Route::post('/create-checkout-session', [PaymentController::class, 'createCheckoutSession'])->name('payment.createCheckoutSession');
Route::get('payment/success/{id}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/cancel/{id}', [PaymentController::class, 'cancel'])->name('payment.cancel');

Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [RegisterUserController::class, 'login'])->name('login');
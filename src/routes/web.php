<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'verified'])->group( function() {
    Route::post('/comment', [ProductController::class, 'storeComment'])->name('comment.store');
    Route::patch('/comment/reply/{id}', [ProductController::class, 'reply'])->name('comment.reply');
    Route::get('/sell', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/sell', [ProductController::class, 'store'])->name('product.store');
    Route::post('/likes/{id}', [ProductController::class, 'like'])->name('like');

    Route::get('/mypage', [UserController::class, 'index'])->name('my-page');
    Route::get('/mypage/profile', [UserController::class, 'create'])->name('profile.create');
    Route::post('/mypage/profile/{id}', [UserController::class, 'store'])->name('profile.store');
    Route::patch('/mypage/profile/update/{id}', [UserController::class, 'update'])->name('profile.update');
    Route::get('/purchase/address/{id}', [UserController::class, 'edit'])->name('address');
    Route::patch('/purchase/address/update/{id}', [UserController::class, 'updateAddress'])->name('address.update');

    Route::get('/purchase/{id}', [PaymentController::class, 'showPurchaseForm'])->name('purchase');
    Route::post('/create-checkout-session', [PaymentController::class, 'createCheckoutSession'])->name('purchase.store');
    Route::get('payment/success/{purchaseId}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('payment/cancel/{purchaseId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});


require __DIR__ . '/auth.php';

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
Route::get('/item/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/sell', [ProductController::class, 'create'])->name('product.create');
Route::post('/products/sell', [ProductController::class, 'store'])->name('product.store');
Route::post('/products/comment', [ProductController::class, 'storeComment'])->name('comment.store');
Route::get('/purchase/{id}', [ProductController::class, 'show'])->name('purchase');
Route::get('/purchase/address/{id}', [ProductController::class, 'edit'])->name('address');
Route::patch('/purchase/address/update/{id}', [ProductController::class, 'update'])->name('address.update');

Route::get('/mypage', [UserController::class, 'index'])->name('my-page');
Route::get('/mypage/profile', [UserController::class, 'create'])->name('profile.create');
Route::post('/mypage/profile/{id}', [UserController::class, 'store'])->name('profile.store');



Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [RegisterUserController::class, 'login'])->name('login');
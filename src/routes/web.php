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
Route::resource('products', ProductController::class)->except(['index','destroy']);
Route::post('/products/comment', [ProductController::class, 'storeComment'])->name('store.comment');

Route::resource('payment', PaymentController::class);

Route::get('/mypage/profile', [UserController::class, 'create'])->name('create.profile');
Route::post('/profile/create/{id}', [UserController::class, 'store'])->name('store.profile');



Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [RegisterUserController::class, 'login'])->name('login');
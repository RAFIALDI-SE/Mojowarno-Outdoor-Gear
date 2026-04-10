<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\TermsConditionController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/home', [HomeController::class, 'index'])->name('home');





Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');

Route::get('/product-images/{productId}', [ProductImageController::class, 'index'])->name('product_images.index');
Route::post('/product-images/store', [ProductImageController::class, 'store'])->name('product_images.store');
Route::delete('/product-images/delete/{id}', [ProductImageController::class, 'destroy'])->name('product_images.delete');

Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
Route::get('/contents/create', [ContentController::class, 'create'])->name('contents.create');
Route::post('/contents/store', [ContentController::class, 'store'])->name('contents.store');
Route::get('/contents/edit/{id}', [ContentController::class, 'edit'])->name('contents.edit');
Route::put('/contents/update/{id}', [ContentController::class, 'update'])->name('contents.update');
Route::delete('/contents/delete/{id}', [ContentController::class, 'destroy'])->name('contents.delete');

Route::get('/terms-conditions', [TermsConditionController::class, 'index'])->name('terms.index');
Route::get('/terms-conditions/create', [TermsConditionController::class, 'create'])->name('terms.create');
Route::post('/terms-conditions/store', [TermsConditionController::class, 'store'])->name('terms.store');
Route::get('/terms-conditions/edit/{id}', [TermsConditionController::class, 'edit'])->name('terms.edit');
Route::put('/terms-conditions/update/{id}', [TermsConditionController::class, 'update'])->name('terms.update');
Route::delete('/terms-conditions/delete/{id}', [TermsConditionController::class, 'destroy'])->name('terms.delete');


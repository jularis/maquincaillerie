<?php

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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{cart}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/panier/count', [CartController::class, 'count'])->name('cart.count');

// Checkout
Route::get('/commande', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/commande', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/commande/confirmation/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
    Route::get('/inscription', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

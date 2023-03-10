<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'login']);
Route::get('login/google', [AuthController::class, 'redirectToGoogle']);

Route::post('register', [AuthController::class, 'register']);

Route::get('token', [AuthController::class, 'token']);

Route::group(['middleware' => ['token']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['token']], function () {
    Route::get('account', [AccountController::class, 'getAccount']);
    Route::post('account', [AccountController::class, 'updateAccount']);

    Route::get('account/product', [AccountController::class, 'getProducts']);
    Route::post('account/product', [AccountController::class, 'createProduct']);
    Route::post('account/product/{id}', [AccountController::class, 'updateProduct']);
    Route::delete('account/product/{id}', [AccountController::class, 'deleteProduct']);

    Route::get('account/orders', [AccountController::class, 'getOrders']);
});

Route::group(['middleware' => ['token']], function () {
    Route::get('cart', [CartController::class, 'getCart']);

    Route::post('cart/item', [CartController::class, 'addToCart']);
    Route::delete('cart/item', [CartController::class, 'deleteCartItems']);

    Route::post('cart/item/{cartItemId}', [CartController::class, 'updateCartItem']);
    Route::delete('cart/item/{cartItemId}', [CartController::class, 'deleteCartItem']);
});

Route::group(['middleware' => ['token']], function () {
    Route::post('checkout', [CheckoutController::class, 'checkout']);
});

Route::group([], function () {
    Route::get('product', [ProductController::class, 'getProduct']);
    Route::get('product/{id}', [ProductController::class, 'getProductById']);
});

Route::group([], function () {
    Route::get('category', [CategoryController::class, 'getCategories']);
});

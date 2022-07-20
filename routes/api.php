<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth', 'auth:api']], function(){
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);
    Route::get('token', [AuthController::class, 'token']);
});

Route::group(['middleware' => ['auth:api', 'client.credentials']], function () {
    Route::get('account', [AccountController::class, 'getAccount']);
    Route::post('account', [AccountController::class, 'updateAccount']);
});

Route::group([], function() {
    Route::get('product', [ProductController::class, 'getProduct']);
});

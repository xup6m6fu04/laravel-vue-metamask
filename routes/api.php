<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// No need JWT
Route::group(['prefix' => 'auth'], function () {
    // 登入
    Route::post('/login', [AuthController::class, 'login']);
    // 取得 nonce
    Route::post('/nonce', [AuthController::class, 'generateNonce']);
});

// BITWIN Callback

// 用戶支付成功
Route::post('/callback/payment', [OrderController::class, 'payment']);

// Need JWT
Route::group(['middleware' => 'auth:api'], function () {

    // Auth
    Route::group(['prefix' => 'auth'], function () {
        // 登出
        Route::post('/logout', [AuthController::class, 'logout']);
        // 刷新憑證
        Route::post('/refresh', [AuthController::class, 'refresh']);
        // 獲取通過身份驗證的使用者
        Route::post('/me', [AuthController::class, 'me']);
    });

    // Player
    // 建立訂單
    Route::post('/player', [PlayerController::class, 'create']);
    // 查詢訂單
    Route::get('/players', [PlayerController::class, 'get']);
    // 查詢餘額
    Route::get('/player/balance', [PlayerController::class, 'getBalance']);

    // Order
    // 建立訂單
    Route::post('/order', [OrderController::class, 'create']);
    // 查詢訂單
    Route::get('/orders', [OrderController::class, 'get']);
    // 建立付款流程
    Route::post('/order/pay', [OrderController::class, 'pay']);
    // 付款完成更改訂單狀態
    // PS. 只能確保使用者有用我們的網站付款完成，但實際付款到哪以及付了多少，需要另外查詢
    Route::post('/order/paid', [OrderController::class, 'paid']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
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

    // Order

    // 建立訂單
    Route::post('/order', [OrderController::class, 'create']);
    // 查詢訂單
    Route::get('/orders', [OrderController::class, 'get']);
});

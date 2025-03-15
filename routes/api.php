<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PaymentController;



// ------------------------------ Awal 2FA ------------------------------
Route::group([
    'prefix' => '2fa'
], function () {
    // Route::group([
    //     'middleware' => ['auth:api', 'signature']
    // ], function () {
    Route::post('/generate', [Google2FAController::class, 'generate']);
    Route::post('/verify', [Google2FAController::class, 'verifyCode']);
    // });
});
// ------------------------------ Akhir 2FA ------------------------------



// ------------------------------ Awal Authentikasi ------------------------------
Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::group([
      'middleware' => 'auth:api'
    ], function(){
      Route::post('logout', [AuthController::class,'logout']);
      Route::post('refresh', [AuthController::class, 'refresh']);
      Route::get('me', [AuthController::class,'me']);
      
      Route::group([
        'middleware' => 'auth:api'
      ], function () {

      });
      
    });
  });
// ------------------------------ Akhir Authentikasi ------------------------------



Route::group([
    'prefix' => 'menu'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list', [MenuController::class, 'listMenu']);
        Route::post('create', [MenuController::class, 'createMenu']);
        Route::post('available', [MenuController::class, 'getAvailableMenu']);
        Route::post('update', [MenuController::class, 'updateMenu']);
        Route::delete('delete/{id}', [MenuController::class, 'deleteMenu']);
    });
});

Route::group([
    'prefix' => 'pemesanan'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('list', [PemesananController::class, 'listPemesanan']);
        Route::post('create', [PemesananController::class, 'createPemesanan']);
        Route::post('update', [PemesananController::class, 'updatePemesanan']);
        Route::delete('delete/{id}', [PemesananController::class, 'deletePemesanan']);
    });
});


Route::group([
    'prefix' => 'payments'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        // Route::post('/invoice', [PaymentController::class, 'create']);
        Route::post('/webhooks', [PaymentController::class, 'webHook']);
        Route::get('/status', [AuthController::class, 'checkLangganan']);
    });
});
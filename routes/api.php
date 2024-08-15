<?php

use App\Http\Controllers\Auth\ClientController;
use App\Http\Controllers\CaptchaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 用户中心
Route::group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers'], function () {
    Route::middleware('auth:api')->get('/', function (Request $request) {
        return $request->user();
    });
    Route::post('/login', 'UserController@login');
    Route::middleware('developerAuth')->group(function () {
        Route::post('/logout', 'UserController@logout');
        Route::post('/info', 'UserController@info');
    });
    Route::middleware('adminAuth')->group(function () {
        Route::post('/list', 'UserController@list');
        Route::post('/register', 'UserController@register');
        Route::post('/update', 'UserController@update');
        Route::post('/delete', 'UserController@delete');
    });
    Route::get('userInfo', 'UserController@userInfo');
});

Route::group(['prefix' => 'client', 'namespace' => 'App\Http\Controllers\Auth', 'middleware' => 'developerAuth'], function () {
    Route::post('/list', 'ClientController@list');
    Route::post('/create', 'ClientController@create');
    Route::post('/update', 'ClientController@update');
    Route::post('/delete', 'ClientController@delete');
});

Route::group(['prefix' => 'check/client', 'namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::middleware('developerAuth')->group(function () {
        Route::post('/list', 'ClientController@checkList');
        Route::post('/update', 'ClientController@checkUpdate');
    });
});

Route::middleware(['myAuth'])->group(function () {
    Route::get('/oauth-applications', [ClientController::class, 'index'])->name('api.oauth.applications');
    Route::delete('/oauth-applications/{tokenId}', [ClientController::class, 'revoke'])->name('api.oauth.applications.revoke');
});

// 验证码
Route::post('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');

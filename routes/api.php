<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::namespace('Api')->group(function () {
    Route::post('luckywheel/user_list', 'SeamlesWsController@user_list')->name('user_list');
    Route::post('luckywheel/game_callback', 'SeamlesWsController@luckywheel')->name('luckywheel');
    Route::post('huidu_api', 'SeamlesWsController@huidu_api')->name('huidu_callback');
});

Route::namespace('Api')->group(function () {
    Route::post('gold_api', 'SeamlesWsController@gold_api')->name('gold_api');
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::namespace('Frontend')->group(function () {
    Route::get('/', 'HomeController@home');
    Route::get('/home', 'HomeController@index')->name('index');
    Route::get('/tutorial', 'HomeController@tutorial')->name('tutorial');
    Route::get('reff/{id}', 'ReferralController@redirect')->name('referral.redirect');
    Route::get('register/verify-number', 'HomeController@verify')->name('verify');
    Route::get('resend_code', 'HomeController@resend_code')->name('resend_code');
    Route::post('verify_otp', 'HomeController@verify_otp')->name('verify_otp');
    Route::get('/help', 'HomeController@help')->name('help');
    Route::get('/contact', 'HomeController@contact')->name('contact');
    Route::post('apk_game_deposit', 'HomeController@transfer_money')->name('transfer_money');
    Route::get('promotion', 'HomeController@promotion')->name('promotion');
    Route::get('promotion/{slug}', 'HomeController@promotiondetail')->name('promotiond');

    //route checkuser
    Route::post('userCheck', 'HomeController@check')->name('user.check');
    Route::post('userphone', 'HomeController@phone')->name('user.phone');
    Route::post('usernorek', 'HomeController@norek')->name('user.norek');
    Route::post('transfer_money', 'HomeController@transfer_money')->name('transfer_money');
    Route::get('transaksi/gateway/callback', 'TransactionController@callback')->name('transaksi.callback');

    Route::get('backup', 'HomeController@backup')->name('backup');

    Route::middleware(['auth'])->group(function () {
        Route::get('referral', 'ReferralController@index')->name('referral');

        Route::get('slot', 'GameController@slot')->name('slot');
        Route::get('casino', 'GameController@casino')->name('casino');
        Route::get('sportsbook', 'GameController@sportsbook')->name('sportsbook');
        Route::get('arcade', 'GameController@arcade')->name('arcade');
        Route::get('other', 'GameController@other')->name('other');
        Route::get('game_list_click/{id}/{slug}', 'GameController@game_list_click')->name('game_list_click');
        Route::get('show_game_list/{id}/{slug}', 'GameController@show_game_list')->name('show_game_list');

        Route::get('profile', 'UserController@profile')->name('profile');
        Route::get('change_password', 'UserController@change_password')->name('change_password');
        Route::get('profile_information', 'UserController@profile_information')->name('profile_information');
        Route::get('bank_account', 'UserController@bank_account')->name('bank_account');
        Route::post('profile/update-password', 'UserController@update')->name('update.password');
        Route::post('optionalBankCreate', 'HomeController@optionalBankCreate')->name('optionalBankCreate');

        Route::get('deposit', 'TransactionController@transaction')->name('deposit');
        Route::get('withdraw', 'TransactionController@transaction_withdraw')->name('withdraw');

        Route::get('history', 'TransactionController@history')->name('history');
        Route::get('history_withdraw', 'TransactionController@history_withdraw')->name('history_withdraw');
        Route::get('history_game_log', 'TransactionController@history_game_log')->name('history_game_log');

        Route::post('transaksi/deposit', 'TransactionController@posttrx')->name('transaksi.deposit');
        Route::post('transaksi/deposit/auto', 'TransactionController@posttrx_pg')->name('transaksi.deposit.auto');
        Route::post('transaksi/deposit/reload', 'TransactionController@posttrx_reload')->name('transaksi.deposit.reload');
        Route::post('transaksi/withdraw', 'TransactionController@withdraw')->name('transaksi.withdraw');

        Route::get('gameIframe', 'GameController@launch_games')->name('launchgames');
        Route::post('gameIframes', 'GameController@launch_game')->name('launchgame');
    });
});

Route::middleware(['auth'])->namespace('Api')->group(function () {
    Route::any('member/Getbalance2', 'SeamlesWsController@getBalance2')->name('api.balance');
});

Route::namespace('Api')->group(function () {
    Route::get('ip-check', 'SeamlesWsController@testapi')->name('apitest');
});

Route::namespace('Auth')->group(function () {
    Route::get('member/logout', 'LoginController@logout')->name('member.logout');
    Route::post('register-post', 'RegisterController@register')->name('registerpost');
});

Route::get('locale/{lange}', 'LocalizationController@setlang')->name('setlang');

Route::namespace('Backend')->name('admin.')->group(function () {
    Route::get('app/cronsjob', 'DashboardController@cron')->name('cron');
});

Auth::routes();

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


Route::namespace('Backend')->prefix('bo/v2')->name('admin.')->group(function () {
    Route::middleware(['api.auth'])->group(function () {
        Route::post('_get_me_count', 'DashboardController@_get_me_count')->name('dashboard');
        Route::post('_get_latest', 'DashboardController@_get_latest')->name('_get_latest');
        Route::post('get_member_total_balance', 'DashboardController@get_member_total_balance')->name('get_member_total_balance');

        Route::post('account_listing', 'MemberController@account_listing')->name('members.account_listing');
        Route::post('member_details/{id}', 'MemberController@member_details')->name('members.member_details');
        Route::post('transaction_history/{id}', 'MemberController@transaction_history')->name('members.transaction_history');
        Route::post('member_details/{id}/balance', 'MemberController@member_details_balance')->name('members.member_details_balance');
        Route::post('account_password_edit', 'MemberController@account_password_edit')->name('account_password_edit');
        Route::post('update_account_listing', 'MemberController@update_account_listing')->name('update_account_listing');
        Route::post('new_member', 'MemberController@new_member')->name('new_member');
        Route::post('update_provider', 'MemberController@update_provider')->name('update_provider');
        Route::post('update_bank_user', 'MemberController@update_bank')->name('update_bank_user');
        Route::post('update_account_data', 'MemberController@update_data')->name('update_account_data');
        Route::post('update_data_remark', 'MemberController@update_data_remark')->name('update_data_remark');

        Route::post('summary_report', 'ReportController@summary_report')->name('summary_report');
        Route::post('get_member_summary', 'ReportController@get_member_summary')->name('get_member_summary');

        Route::post('agent_promo_settings', 'PromotionController@index')->name('agent_promo_settings');
        Route::post('agent_promo_settings/create', 'PromotionController@create')->name('agent_promo_settings_create');
        Route::post('agent_promo_settings/delete', 'PromotionController@delete')->name('agent_promo_settings_delete');

        Route::post('transaction/detail/{id}', 'DepositController@get_transaction')->name('transaction.detail');
        Route::post('transaction/pending', 'DepositController@index')->name('transaction.pending');
        Route::post('transaction/multiple_reject', 'DepositController@bulkActionReject')->name('transaction.bulkActionReject');
        Route::post('transaction/multiple_approve', 'DepositController@bulkActionapprove')->name('transaction.bulkActionapprove');
        Route::post('transaction/approve/{id}', 'DepositController@approve')->name('transaction.approve');
        Route::post('transaction/reject/{id}', 'DepositController@reject')->name('transaction.reject');
        Route::post('transaction/transaction_history', 'DepositController@transaction_history')->name('transaction.transaction_history');

        Route::post('settings/website', 'SettingController@index')->name('settings');
        Route::post('settings/website/update', 'SettingController@update')->name('settings.update');
        Route::post('settings/api/update/{id}', 'SettingController@edit_api')->name('settings.api.update');

        Route::post('settings/domain_list', 'SettingController@domain_list')->name('settings.domain_list');
        Route::post('settings/domain/add', 'SettingController@add_domain')->name('settings.domain.add');
        Route::post('settings/domain/remove/{id}', 'SettingController@domain_remove')->name('settings.domain.remove');

        Route::post('settings/sliding_banner', 'SettingController@sliding_banner')->name('settings.sliding_banner');
        Route::post('settings/sliding_banner/create', 'SettingController@sliding_banner_create')->name('settings.sliding_banner_create');
        Route::post('settings/sliding_banner/delete/{id}', 'SettingController@sliding_banner_delete')->name('settings.sliding_banner_delete');

        Route::post('banks/account', 'BankController@index')->name('bank.list');
        Route::post('banks/{id}/edit', 'BankController@edit')->name('bank.edit');

        Route::post('banks/create', 'BankController@create')->name('bank.create');
        Route::post('banks/{id}/update', 'BankController@update')->name('bank.update');
        Route::post('banks/{id}/delete', 'BankController@delete')->name('bank.delete');

        Route::post('games/call_players', 'ProviderController@call_players')->name('calls.list');
        Route::post('games/call_list', 'ProviderController@call_list')->name('calls.list');
        Route::post('games/call_apply', 'ProviderController@call_apply')->name('calls.list');
        Route::post('games/call_rtp', 'ProviderController@call_rtp')->name('calls.call_rtp');

        Route::post('brand_management', 'SettingController@brand_management')->name('brand_management');
        Route::post('brand_management/create', 'SettingController@create_brand')->name('create_brand');
        Route::post('brand_management/{id}/delete', 'SettingController@delete_brand')->name('delete_brand');

        Route::post('provider_list', 'ProviderController@provider_list')->name('provider_list');
        Route::post('provider_list/update_provider', 'ProviderController@update_provider')->name('provider_list.update');
        Route::post('provider_list/game_lists/{id}', 'ProviderController@gamelists')->name('provider_list.game_lists');
        Route::post('provider_list/game_lists/{id}/update', 'ProviderController@update_games')->name('provider_list.update_games');
    });

    Route::get('config-clear', function () {
        Artisan::call('optimize:clear');
        return back()->with('success', 'Cache clear successfully');
    });
});

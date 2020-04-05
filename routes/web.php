<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'IndexController@index');
Route::group(['prefix' => 'transfer-money', 'as' => 'transfer_money.'], function () {
    Route::get('/', 'TransferMoneyController@index')->name('index');
    Route::post('/transfer-money','TransferMoneyController@store')->name('store');
    Route::get('/get-cards/{user?}', 'TransferMoneyController@getCards')->name('get_cards');
});

Auth::routes(['verify' => true]);

Route::group(['middleware'=>'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('home/change-password', 'ChangePasswordController@index')->name('change_password.index');
    Route::post('home/change-password', 'ChangePasswordController@store')->name('change_password.store');

    Route::post('/home/update-avatar', 'HomeController@updateAvatar')->name('home.update_avatar');

    Route::get('generate-card-number', function () {
        return response()->json([
            'card' => App\Card::generateCardNumber()
        ]);
    });

    Route::resource('credit_cards', 'CreditCardController')->except(['show','edit','update']);

    Route::group([ 'prefix' => 'admin', 'middleware' => 'admin', 'as' => 'admin.' ], function () {
        Route::get('users', 'AdminController@users')->name('users');
        Route::get('transactions', 'AdminController@transactions')->name('transactions');
    });
});

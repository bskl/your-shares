<?php

use Illuminate\Http\Request;

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

Route::namespace('API')->group(function () {

    Route::get('/transaction', function() {
        $data['user_id'] = 1;
        $data['commission'] = '0.0188';
        $data['date'] = '2017-11-14';
        $data['lot'] = 4;
        $data['price'] = '42.53';
        $data['share_id'] = 2;
        $data['type'] = 2;
        $transaction = App\Models\Transaction::create($data);
        $transaction->refresh()->load('share');

        $event = 'App\\Events\\' . App\Enums\TransactionTypes::getTypeName($transaction->type) . 'TransactionCreated';
        event(new $event($transaction));

    });

    Route::post('/register', 'Auth\RegisterController@store');
    Route::post('/login', 'Auth\LoginController@login');
    //Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail');
    //Route::post('/password/reset', 'ResetPasswordController@reset');
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'Auth\LogoutController@logout');
        Route::get('/locale/{locale}', 'UserController@setLocale');

        Route::get('/data', 'DataController@getData');

        Route::post(  '/portfolio', 'PortfolioController@store');
        Route::put(   '/portfolio/{portfolio}', 'PortfolioController@update');
        Route::delete('/portfolio/{portfolio}', 'PortfolioController@destroy');

        Route::post(  '/share', 'ShareController@store');
        Route::delete('/share/{share}', 'ShareController@destroy');
        Route::get(   '/share/{share}/transactions', 'ShareController@getShareTransactions'); 

        Route::post('/transaction', 'TransactionController@store');

        Route::get('/symbol/search', 'SymbolController@searchSymbol');
    });
});

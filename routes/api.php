<?php

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
    Route::post('/register', 'Auth\RegisterController@store');
    Route::get('/confirm/{token}', 'Auth\RegisterController@verifyConfirmationCode');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'Auth\LogoutController@logout');
        Route::get('/locale/{locale}', 'UserController@setLocale');

        Route::get('/data', 'DataController@getData');

        Route::post('/portfolio', 'PortfolioController@store');
        Route::get('/portfolio/{id}', 'PortfolioController@show');
        Route::get('/portfolio/{id}/transactions/{type}', 'PortfolioController@getTransactionsByType');
        Route::get('/portfolio/{id}/transactions/{type}/{year}', 'PortfolioController@getTransactionsByTypeAndYear');
        Route::put('/portfolio/{id}', 'PortfolioController@update');
        Route::delete('/portfolio/{id}', 'PortfolioController@destroy');

        Route::post('/share', 'ShareController@store');
        Route::get('/share/{id}', 'ShareController@show');
        Route::delete('/share/{id}', 'ShareController@destroy');
        Route::get('/share/{id}/transactions', 'ShareController@getTransactions');
        Route::get('/share/{id}/transactions/{type}', 'ShareController@getTransactionsByType');
        Route::get('/share/{id}/transactions/{type}/{year}', 'ShareController@getTransactionsByTypeAndYear');

        Route::post('/transaction', 'TransactionController@store');
        Route::delete('/transaction/{id}', 'TransactionController@destroy');

        Route::get('/symbol/data', 'SymbolController@getData');
        Route::get('/symbol', 'SymbolController@getSymbols');
    });
});

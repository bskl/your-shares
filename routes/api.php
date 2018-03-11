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
        Route::put('/portfolio/{portfolio}', 'PortfolioController@update');
        Route::delete('/portfolio/{portfolio}', 'PortfolioController@destroy');

        Route::post('/share', 'ShareController@store');
        Route::delete('/share/{share}', 'ShareController@destroy');
        Route::get('/share/{share}/transactions', 'ShareController@getShareTransactions');

        Route::post('/transaction', 'TransactionController@store');

        Route::get('/symbol/search', 'SymbolController@searchSymbol');
    });
});

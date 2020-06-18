<?php

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

        Route::get('/portfolios', 'PortfolioController@index');
        Route::post('/portfolios', 'PortfolioController@store');
        Route::get('/portfolios/{portfolio}', 'PortfolioController@show');
        Route::get('/portfolios/{portfolio}/transactions/{type}', 'PortfolioController@getTransactionsByType');
        Route::get('/portfolios/{portfolio}/transactions/{type}/{year}', 'PortfolioController@getTransactionsByTypeAndYear');
        Route::put('/portfolios/{portfolio}', 'PortfolioController@update');
        Route::delete('/portfolios/{portfolio}', 'PortfolioController@destroy');

        Route::post('/shares', 'ShareController@store');
        Route::get('/shares/{share}', 'ShareController@show');
        Route::delete('/shares/{share}', 'ShareController@destroy');
        Route::get('/shares/{share}/transactions', 'ShareController@getTransactions');
        Route::get('/shares/{share}/transactions/{type}', 'ShareController@getTransactionsByType');
        Route::get('/shares/{share}/transactions/{type}/{year}', 'ShareController@getTransactionsByTypeAndYear');

        Route::post('/transactions', 'TransactionController@store');
        Route::delete('/transactions/{transaction}', 'TransactionController@destroy');

        Route::get('/symbols', 'SymbolController@index');
        Route::get('/symbols/update', 'SymbolController@update');
    });
});

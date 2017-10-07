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
        Route::post(  '/portfolio/add-symbol', 'PortfolioController@addSymbolToPortfolio');

        Route::get(  '/symbol/search', 'SymbolController@searchSymbol');
    });
});

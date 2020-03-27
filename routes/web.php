<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Catch-all Route...
Route::any('/{view?}', function () {
    return view('index');
})->where('view', '[\/\w\.-]*')->name('home');

Route::get('password/reset/{token}', 'Auth\LoginController@login')->name('password.reset');

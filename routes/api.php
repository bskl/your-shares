<?php

use App\Http\Controllers\API\DataController;
use App\Http\Controllers\API\PortfolioController;
use App\Http\Controllers\API\ShareController;
use App\Http\Controllers\API\SymbolController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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
    Route::get('/confirm/{token}', [UserController::class, 'verifyConfirmationCode'])
        ->middleware('guest');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/locale/{locale}', [UserController::class, 'setLocale']);

        Route::get('/data', [DataController::class, 'getData']);

        Route::get('/portfolios', [PortfolioController::class, 'index']);
        Route::post('/portfolios', [PortfolioController::class, 'store']);
        Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show']);
        Route::get('/portfolios/{portfolio}/transactions/{type}', [PortfolioController::class, 'getTransactionsByType']);
        Route::get('/portfolios/{portfolio}/transactions/{type}/{year}', [PortfolioController::class, 'getTransactionsByTypeAndYear']);
        Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update']);
        Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy']);

        Route::post('/shares', [ShareController::class, 'store']);
        Route::get('/shares/{share}', [ShareController::class, 'show']);
        Route::delete('/shares/{share}', [ShareController::class, 'destroy']);
        Route::get('/shares/{share}/transactions', [ShareController::class, 'getTransactions']);
        Route::get('/shares/{share}/transactions/{type}', [ShareController::class, 'getTransactionsByType']);
        Route::get('/shares/{share}/transactions/{type}/{year}', [ShareController::class, 'getTransactionsByTypeAndYear']);

        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);

        Route::get('/symbols', [SymbolController::class, 'index']);
        Route::get('/symbols/update', [SymbolController::class, 'update']);
    });
});

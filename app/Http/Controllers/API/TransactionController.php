<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use App\Http\Requests\API\TransactionRequest;
use App\Events\BuyingTransactionCreated;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param  TransactionRequest     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(TransactionRequest $request)
    {
        $data = $request->all();
        $data['amount'] = $data['commission_price'] = $data['average'] = $data['sale_gain'] = 0;

        $transaction = Transaction::create($data);

        event(new BuyingTransactionCreated($transaction));

        return response()->json($transaction->share);
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param  TransactionRequest     $request
     * @param  App\Models\Portfolio $portfolio
     * @return App\Models\Portfolio $portfolio
     */
    public function update()
    {
        
    }

    /**
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy()
    { 
        
    }
}
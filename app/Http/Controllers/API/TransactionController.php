<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use App\Enums\TransactionTypes;
use App\Http\Requests\API\TransactionRequest;
use App\Events\BuyingTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

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
        $data['amount'] = $data['commission_price'] = $data['average'] = $data['sale_gain'] = '0';

        $transaction = Transaction::create($data);

        $eventType = TransactionTypes::getTypeName($transaction->type);

        $event = $eventType . "Transaction";

        Event::fire($event, array($transaction));

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
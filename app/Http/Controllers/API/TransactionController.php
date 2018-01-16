<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use App\Models\Share;
use App\Enums\TransactionTypes;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param  TransactionRequest     $request
     * @return App\Models\Transaction $transaction
     */
    public function store(TransactionRequest $request)
    {
        $share = Share::findOrFail($request->share_id);
        $this->authorize('create', $share);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $transaction = new Transaction();
        $transaction->fill($data);

        $transaction->price = $data['price'];
        $transaction->dividend_gain = $data['dividend_gain'];
        
        $transaction->save();
        $transaction->refresh()->load('share');

        $event = 'App\\Events\\' . TransactionTypes::getTypeName($transaction->type) . 'TransactionCreated';
        event(new $event($transaction));

        return response()->json($transaction->share);
    }

    /**
     * Update given transaction instance after a valid request.
     *
     * @param  TransactionRequest     $request
     * @param  App\Models\Transaction $transaction
     * @return App\Models\Transaction $transaction
     */
    public function update()
    {
        
    }

    /**
     * Delete a portfolio.
     *
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function destroy()
    { 
        
    }
}
<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use App\Enums\TransactionTypes;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

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
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $transaction = Transaction::create($data);
        $transaction->refresh()->load('share');

        $event = 'App\\Events\\' . TransactionTypes::getTypeName($transaction->type) . 'TransactionCreated';
        event(new $event($transaction));

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
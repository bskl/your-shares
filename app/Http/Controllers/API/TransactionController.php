<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Models\Share;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param TransactionRequest $request
     *
     * @return App\Models\Transaction $transaction
     */
    public function store(TransactionRequest $request)
    {
        $share = Share::findOrFail($request->share_id);
        $this->authorize('create', $share);

        $data = $request->all();
        $transaction = new Transaction();
        $transaction->fill($data);

        $transaction->user_id = auth()->user()->id;
        $transaction->price = $data['price'];
        $transaction->dividend_gain = $data['dividend_gain'];

        $transaction->save();
        $transaction->refresh()->load('share');

        $event = 'App\\Events\\'.TransactionTypes::getTypeName($transaction->type).'TransactionCreated';
        event(new $event($transaction));

        $transaction->share->load('portfolio');

        return response()->json($transaction->share);
    }

    /**
     * Update given transaction instance after a valid request.
     *
     * @param TransactionRequest     $request
     * @param App\Models\Transaction $transaction
     *
     * @return App\Models\Transaction $transaction
     */
    public function update()
    {
    }

    /**
     * Delete a portfolio.
     *
     * @param Transaction $transaction
     *
     * @return JsonResponse
     */
    public function destroy()
    {
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Models\Share;
use App\Models\Transaction;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param TransactionRequest $request
     *
     * @return \App\Http\Resources\Portfolio $portfolio
     */
    public function store(TransactionRequest $request)
    {
        $share = Share::findOrFail($request->share_id);
        $this->authorize('create', $share);

        try {
            $data = $request->all();
            $transaction = new Transaction();
            $transaction->fill($data);

            $transaction->user_id = auth()->user()->id;
            $transaction->price = (string) $data['price'];
            $transaction->dividend_gain = (string) $data['dividend_gain'];
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['transaction' => trans('app.transaction.create_error')]
            );
        }

        try {
            $transaction->save();

            $event = 'App\\Events\\'.$transaction->type->key.'TransactionCreated';
            event(new $event($transaction));

            return new PortfolioResource($transaction->share->portfolio);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['transaction' => trans('app.transaction.create_error')]
            );
        }
    }

    /**
     * Delete a transaction instance.
     *
     * @param int $id
     *
     * @return \App\Http\Resources\Portfolio $portfolio
     */
    public function destroy(int $id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize($transaction);

        try {
            $event = 'App\\Events\\'.$transaction->type->key.'TransactionDeleted';
            event(new $event($transaction));

            $portfolio = $transaction->share->portfolio;
            $transaction->delete();

            return new PortfolioResource($portfolio);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.transaction.delete_error')]
            );
        }
    }
}

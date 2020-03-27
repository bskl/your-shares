<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\Share as ShareResource;
use App\Http\Resources\Transaction as TransactionResource;
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
     * @return \Illuminate\Http\JsonResponse
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

            $portfolio = $share->portfolio->makeHidden('shares')->refresh();
            $share = $share->makeHidden(['portfolio', 'symbol', 'transactions'])->refresh();
            $transaction = $transaction->makeHidden('share')->refresh();

            return $this->respondSuccess([
                'portfolio' => new PortfolioResource($portfolio),
                'share' => new ShareResource($share),
                'transaction' => new TransactionResource($transaction)
            ], trans('app.transaction.create_success'));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize($transaction);

        try {
            $event = 'App\\Events\\'.$transaction->type->key.'TransactionDeleted';
            event(new $event($transaction));

            $portfolio = $transaction->share->portfolio->makeHidden('shares')->refresh();
            $share = $transaction->share->makeHidden(['portfolio', 'symbol', 'transactions'])->refresh();

            $transaction->delete();

            return $this->respondSuccess([
                'portfolio' => new PortfolioResource($portfolio),
                'share' => new ShareResource($share),
            ], trans('app.transaction.delete_success'));
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.transaction.delete_error')]
            );
        }
    }
}

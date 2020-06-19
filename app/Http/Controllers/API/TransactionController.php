<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\Share as ShareResource;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param \App\Http\Requests\API\TransactionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TransactionRequest $request)
    {
        $this->authorize(Transaction::class);

        try {
            DB::beginTransaction();

            $transaction = new Transaction();

            $transaction->fill($request->validated());
            $transaction->price = (string) $request->price;
            $transaction->dividend_gain = (string) $request->dividend_gain;
            $transaction->save();

            DB::commit();

            $portfolio = $transaction->share->portfolio->makeHidden('shares')->refresh();
            $share = $transaction->share->load('transactions')->makeHidden('portfolio')->refresh();

            return $this->respondSuccess([
                'portfolio'   => new PortfolioResource($portfolio),
                'share'       => new ShareResource($share),
            ], trans('app.transaction.create_success'));
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['transaction' => trans('app.transaction.create_error')]
            );
        }
    }

    /**
     * Delete a transaction instance.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize($transaction);

        try {
            DB::beginTransaction();

            $transaction->delete();

            DB::commit();

            $portfolio = $transaction->share->portfolio->makeHidden('shares')->refresh();
            $share = $transaction->share->load('transactions')->makeHidden('portfolio')->refresh();

            return $this->respondSuccess([
                'portfolio' => new PortfolioResource($portfolio),
                'share'     => new ShareResource($share),
            ], trans('app.transaction.delete_success'));
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.transaction.delete_error')]
            );
        }
    }
}

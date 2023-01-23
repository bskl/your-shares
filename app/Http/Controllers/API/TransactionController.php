<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\Share as ShareResource;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\Symbol;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Create a new transaction instance for auth user after a valid request.
     *
     * @param  \App\Http\Requests\API\TransactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $this->authorize(Transaction::class);

        try {
            DB::beginTransaction();

            $transaction = new Transaction();

            $transaction->fill($request->validated());
            if ($transaction->type->in([TransactionType::MergerOut, TransactionType::MergerIn]) &&
                ! is_null($symbol = Symbol::firstWhere('id', $request->symbol_id))) {
                $transaction->symbol_code = $symbol->code;
            }
            $transaction->save();

            DB::commit();

            $portfolio = $transaction->share->portfolio->refresh();
            $transactions = $transaction->share->transactions;

            return $this->respondSuccess([
                'portfolio' => new PortfolioResource($portfolio),
                'transactions' => new TransactionResource($transactions),
            ], trans('app.transaction.create_success'), Response::HTTP_CREATED);
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
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction): JsonResponse
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
                'share' => new ShareResource($share),
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

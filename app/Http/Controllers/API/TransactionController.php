<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransactionRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\Share as ShareResource;
use App\Models\Share;
use App\Models\Transaction;
use App\Traits\TransactionCalculator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use TransactionCalculator;

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
            $data = $request->validated();
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
            DB::beginTransaction();

            $transaction->save();

            $portfolio = $transaction->share->portfolio;
            $share = $transaction->share;
            $function = 'handle'.$transaction->type->key.'Calculations';
            $this->$function($portfolio, $share, $transaction);

            DB::commit();

            $portfolio = $portfolio->makeHidden('shares')->refresh();
            $share = $share->load('transactions')->makeHidden('portfolio')->refresh();

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
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->authorize($transaction);

        try {
            DB::beginTransaction();

            $portfolio = $transaction->share->portfolio;
            $share = $transaction->share;
            $function = 'handleDeleted'.$transaction->type->key.'Calculations';
            $this->$function($portfolio, $share, $transaction);

            $transaction->delete();

            DB::commit();

            $portfolio = $portfolio->makeHidden('shares')->refresh();
            $share = $share->load('transactions')->makeHidden('portfolio')->refresh();

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

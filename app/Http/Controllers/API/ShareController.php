<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ShareRequest;
use App\Http\Resources\Share as ShareResource;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\Share;
use Illuminate\Http\Response;

class ShareController extends Controller
{
    /**
     * Show the share instance for the given id.
     *
     * @param \App\Models\Share $share
     *
     * @return \App\Http\Resources\Share $share
     */
    public function show(Share $share)
    {
        $this->authorize($share);

        return new ShareResource($share);
    }

    /**
     * Create a new share instance for auth user after a valid request.
     *
     * @param \App\Http\Requests\API\ShareRequest $request
     *
     * @return \App\Http\Resources\Share $share
     */
    public function store(ShareRequest $request)
    {
        $this->authorize(Share::class);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        try {
            $share = Share::create($data);
            $share->refresh()->load('symbol');

            return (new ShareResource($share))->additional([
                'message' => trans('app.share.create_success'),
            ]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['share' => trans('app.share.create_error')]
            );
        }
    }

    /**
     * Delete a share instance.
     *
     * @param \App\Models\Share $share
     *
     * @return \App\Http\Resources\Share $share
     */
    public function destroy(Share $share)
    {
        $this->authorize($share);

        if ($share->total_purchase_amount->isPositive()) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.share.cannot_deleted')]
            );
        }

        try {
            $share->delete();

            return (new ShareResource([]))->additional([
                'message' => trans('app.share.delete_success'),
            ]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.share.delete_error')]
            );
        }
    }

    /**
     * Get share instance with all transactions.
     *
     * @param \App\Models\Share $share
     *
     * @return \App\Http\Resources\Transaction
     */
    public function getTransactions(Share $share)
    {
        $this->authorize('view', $share);

        return TransactionResource::collection($share->transactions);
    }

    /**
     * Get share instance with transactions by type.
     *
     * @param \App\Models\Share $share
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByType(Share $share, string $type)
    {
        $this->authorize('view', $share);

        return $this->getTransactionsByModelAndType($share, $type);
    }

    /**
     * Get share instance with transactions by type and year.
     *
     * @param \App\Models\Share $share
     * @param string $type
     * @param int    $year
     *
     * @return \App\Http\Resources\Transaction $transactions
     */
    public function getTransactionsByTypeAndYear(Share $share, string $type, int $year)
    {
        $this->authorize('view', $share);

        $transactionType = $this->getTransactionType($type);

        $transactions = $share->transactions()
                              ->whereIn('type', $transactionType['value'])
                              ->whereYear('date_at', $year)
                              ->get();

        return TransactionResource::collection($transactions);
    }
}

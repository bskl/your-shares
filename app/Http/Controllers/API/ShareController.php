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
     * @param int $id
     *
     * @return \App\Http\Resources\Share $share
     */
    public function show(int $id)
    {
        $share = Share::findOrFail($id);

        $this->authorize($share);

        return new ShareResource($share);
    }

    /**
     * Create a new share instance for auth user after a valid request.
     *
     * @param ShareRequest $request
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

            return new ShareResource($share);
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
     * @param int $id
     *
     * @return \App\Http\Resources\Share $share
     */
    public function destroy(int $id)
    {
        $share = Share::findOrFail($id);

        $this->authorize($share);

        if ($share->total_purchase_amount->isPositive()) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.share.cannot_deleted')]
            );
        }

        try {
            $share->delete();

            return new ShareResource([]);
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
     * @param int $id
     *
     * @return \App\Http\Resources\Share $share
     */
    public function getTransactions(int $id)
    {
        $share = Share::with('transactions')->findOrFail($id);

        $this->authorize('view', $share);

        return new ShareResource($share);
    }

    /**
     * Get share instance with transactions by type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByType(int $id, string $type)
    {
        $share = Share::findOrFail($id);

        $this->authorize('view', $share);

        return $this->getTransactionsByModelAndType($share, $type);
    }

    /**
     * Get share instance with transactions by type and year.
     *
     * @param int    $id
     * @param string $type
     * @param int    $year
     *
     * @return \App\Http\Resources\Transaction $transactions
     */
    public function getTransactionsByTypeAndYear(int $id, string $type, int $year)
    {
        $share = Share::findOrFail($id);

        $this->authorize('view', $share);

        $transactionType = $this->getTransactionType($type);

        $transactions = $share->transactions()
                              ->whereIn('type', $transactionType['value'])
                              ->whereYear('date_at', $year)
                              ->get();

        return TransactionResource::collection($transactions);
    }
}

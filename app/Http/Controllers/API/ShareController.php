<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ShareRequest;
use App\Models\Share;

class ShareController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $share = Share::findOrFail($id);

        $this->authorize($share);

        return response()->json($share->symbol->only('id', 'code'));
    }

    /**
     * Create a new share instance for auth user after a valid request.
     *
     * @param ShareRequest $request
     *
     * @return App\Models\Portfolio $portfolio
     */
    public function store(ShareRequest $request)
    {
        $this->authorize(Share::class);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        try {
            $share = Share::create($data);
            $share->refresh()->load('symbol');

            return response()->json($share);
        } catch (\Exception $e) {
            return response()->json(
                ['messages' => '', 'errors' => [['share' => trans('app.share.create_error')]]],
                422
            );
        }
    }

    /**
     * Update given share instance after a valid request.
     *
     * @param ShareRequest     $request
     * @param App\Models\Sahre $portfolio
     *
     * @return App\Models\Share $portfolio
     */
    public function update()
    {
    }

    /**
     * Delete a share.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $share = Share::findOrFail($id);

        $this->authorize($share);

        if ($share->total_amount != 0) {
            return response()->json(
                trans('app.share.delete_error'),
                401
            );
        }

        try {
            $share->delete();

            return response()->json();
        } catch (\Exception $e) {
            return response()->json(
                trans('app.share.delete_error'),
                422
            );
        }
    }

    /**
     * Get share's all transactions.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getTransactions($id)
    {
        $share = Share::findOrFail($id);

        $this->authorize('view', $share);

        $share = $share->refresh()->load('transactions');

        return response()->json($share);
    }

    /**
     * Get share's transactions by type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return JsonResponse
     */
    public function getTransactionsByType($id, $type)
    {
        $share = Share::findOrFail($id);

        $this->authorize('view', $share);

        return $this->getTransactionsByModelAndType($share, $type);
    }

    /**
     * Get share's all transactions.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getTransactionsByTypeAndYear($id, $type, $year)
    {
        $share = Share::findOrFail($id);

        $this->authorize('view', $share);

        $transactions = $share->transactions()
                              ->whereType(TransactionType::getValue(ucfirst($type)))
                              ->whereYear('date_at', $year)
                              ->get();

        return response()->json($transactions);
    }
}

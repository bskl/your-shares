<?php

namespace App\Http\Controllers\API;

use App\Models\Share;
use App\Http\Requests\API\ShareRequest;
use App\Http\Controllers\Controller;

class ShareController extends Controller
{
    /**
     * Create a new share instance for auth user after a valid request.
     *
     * @param  ShareRequest     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(ShareRequest $request)
    {
        $this->authorize(Share::class);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $share = Share::create($data);
        $share->refresh()->load('symbol');

        return response()->json($share);
    }

    /**
     * Update given share instance after a valid request.
     *
     * @param  ShareRequest     $request
     * @param  App\Models\Sahre $portfolio
     * @return App\Models\Share $portfolio
     */
    public function update()
    {
        
    }

    /**
     * Delete a share.
     *
     * @param Share $share
     * @return JsonResponse
     */
    public function destroy(Share $share)
    {
        $this->authorize($share);

        $share->delete();
        
        return response()->json();   
    }

    /**
     * Get share's all transactions.
     *
     * @param Share $share
     * @return JsonResponse
     */
    public function getShareTransactions(Share $share)
    {
        $share = $share->refresh()->load('transactions');

        return response()->json($share);
    }
}
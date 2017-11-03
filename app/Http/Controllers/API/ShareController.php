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
     * Update given portfolio instance after a valid request.
     *
     * @param  PortfolioRequest     $request
     * @param  App\Models\Portfolio $portfolio
     * @return App\Models\Portfolio $portfolio
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
}
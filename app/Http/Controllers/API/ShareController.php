<?php

namespace App\Http\Controllers\API;

use App\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Create a new share instance for auth user after a valid request.
     *
     * @param  Request     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['lot'] = 0;
        $data['average'] = 0;

        $share = Share::create($data);
        $share->load('symbol');

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
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy()
    { 
        
    }
}
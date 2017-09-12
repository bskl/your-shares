<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
use App\Http\Requests\API\PortfolioRequest;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param  PortfolioRequest     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(PortfolioRequest $request)
    {
        $order = Auth()->user()->portfolios()->count();
        $data = $request->all();
        $data['order'] = ++$order;

        Auth()->user()->portfolios()->create($data);

        $portfolio = Portfolio::select('id', 'user_id', 'name', 'order')
                              ->where('user_id', Auth()->user()->id)
                              ->get()
                              ->last();

        return response()->json($portfolio);
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param  PortfolioRequest     $request
     * @param Portfolio $portfolio
     * @return App\Models\Portfolio $portfolio
     */
    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        $portfolio->update($request->only('name'));
        
        return response()->json($portfolio->only('id', 'user_id', 'name', 'order'));
    }

    /**
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy(Portfolio $portfolio)
    { 
        $portfolio->delete();

        return response()->json();
    }
}
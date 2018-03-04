<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param PortfolioRequest $request
     *
     * @return App\Models\Portfolio $portfolio
     */
    public function store(PortfolioRequest $request)
    {
        $this->authorize(Portfolio::class);

        $order = Portfolio::byCurrentUser()->count();
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['order'] = ++$order;

        $portfolio = Portfolio::create($data);

        $portfolio->refresh()->load('shares');

        return response()->json($portfolio);
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param PortfolioRequest     $request
     * @param App\Models\Portfolio $portfolio
     *
     * @return App\Models\Portfolio $portfolio
     */
    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        $this->authorize($portfolio);

        $portfolio->update($request->all());

        return response()->json($portfolio);
    }

    /**
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function destroy(Portfolio $portfolio)
    {
        $this->authorize($portfolio);

        $portfolio->delete();

        return response()->json();
    }
}

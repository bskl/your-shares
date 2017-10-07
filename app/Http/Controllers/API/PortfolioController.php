<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
use App\Contracts\PortfolioRepository;
use App\Http\Requests\API\PortfolioRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * The portfolios instance.
     */
    protected $portfolios;

    /**
     * Create a new controller instance.
     *
     * @param  App\Contracts\PortfolioRepository  $portfolios
     * @return void
     */
    public function __construct(PortfolioRepository $portfolios)
    {
        $this->portfolios = $portfolios;
    }

    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param  PortfolioRequest     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(PortfolioRequest $request)
    {
        $order = $this->portfolios->count();
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['order'] = ++$order;

        $portfolio = $this->portfolios->create($data);

        return response()->json($portfolio);
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param  PortfolioRequest     $request
     * @param  App\Models\Portfolio $portfolio
     * @return App\Models\Portfolio $portfolio
     */
    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        $this->portfolios->update($portfolio, $request->all());
        
        return response()->json($portfolio);
    }

    /**
     * Delete a portfolio.
     *
     * @param Portfolio $portfolio
     * @return JsonResponse
     */
    public function destroy(Portfolio $portfolio)
    { 
        $this->portfolios->delete($portfolio);

        return response()->json();
    }
}
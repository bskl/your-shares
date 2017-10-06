<?php

namespace App\Http\Controllers\API;

use App\Contracts\PortfolioSymbolRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortfolioSymbolController extends Controller
{
    /**
     * The portfolioSymbols instance.
     */
    protected $portfolioSymbols;

    /**
     * Create a new controller instance.
     *
     * @param  App\Contracts\PortfolioSymbolRepository  $portfolioSymbols
     * @return void
     */
    public function __construct(PortfolioSymbolRepository $portfolioSymbols)
    {
        $this->portfolioSymbols = $portfolioSymbols;
    }

    /**
     * Create a new portfolio symbol instance for auth user after a valid request.
     *
     * @param  Request     $request
     * @return App\Models\Portfolio $portfolio
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['symbol_id'] = $data['symbol_id']['id'];
        $data['share'] = 0;
        $data['average'] = '0';

        $portfolioSymbol = $this->portfolioSymbols->create($data);

        return response()->json($portfolioSymbol);
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
<?php

namespace App\Http\Controllers\API;

use App\Models\Symbol;
use App\Contracts\SymbolRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * The symbols instance.
     */
    protected $symbols;
     
    /**
     * Create a new controller instance.
     *
     * @param  App\Portfolios\SymbolRepository  $symbols
     * @return void
     */
    public function __construct(SymbolRepository $symbols)
    {
        $this->symbols = $symbols;
    }

    /**
     * Add the symbol on the given portfolio.
     *
     * @param  App\Models\Symbol     $symbol
     * @return App\Models\Portfolio  $portfolio
     */
    public function addSymbolToPortfolio(Request $request)
    {
        $data = $request->all();
        $symbol = Symbol::findOrFail($data['symbol_id']);
        $symbol->portfolios()->sync($data['portfolio_id']);

        return response()->json();
    }
}
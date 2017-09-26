<?php

namespace App\Http\Controllers\API;

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
     * @param  App\Contracts\SymbolRepository  $symbols
     * @return void
     */
    public function __construct(SymbolRepository $symbols)
    {
        $this->symbols = $symbols;
    }

    /**
     * Search the symbol on the given text.
     *
     * @param  Illuminate\Http\Request  $request
     * @return App\Models\Symbol        $symbols
     */
    public function searchSymbol(Request $request)
    {
        $data = trim($request->get('q'));

        $symbols = $this->symbols->searchByCode($data);

        return response()->json($symbols);
    }
}
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Symbol;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * Search the symbol on the given text.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return App\Models\Symbol $symbols
     */
    public function searchSymbol(Request $request)
    {
        $data = trim($request->get('q'));

        $symbols = Symbol::select('id', 'code', 'last_price')
                          ->where('code', 'LIKE', "%$data%")
                          ->get();

        return response()->json($symbols);
    }
}

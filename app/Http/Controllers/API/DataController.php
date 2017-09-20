<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
use App\Models\Symbol;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
    * Get a set of application data.
    *
    * @param  Request $request
    * @return JsonResponse
    */
    public function getData(Request $request)
    {
        return [
            'user' => Auth()->user(),
            'portfolios' => Portfolio::byCurrentUSer()->with('symbols')->get(),
            'symbols' => Symbol::get(),
        ];
    }
}
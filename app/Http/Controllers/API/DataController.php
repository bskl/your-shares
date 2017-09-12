<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
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
            'user' => Auth()->user()->only('id', 'name', 'email'),
            'portfolios' => Portfolio::select('id', 'user_id', 'name', 'order')
                                     ->where('user_id', Auth()->user()->id)
                                     ->get(),
        ];
    }
}
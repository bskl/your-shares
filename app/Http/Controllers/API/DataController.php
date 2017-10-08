<?php

namespace App\Http\Controllers\API;

use App\Models\Portfolio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Get a set of application data.
     *
     * @return JsonResponse
     */
    public function getData()
    {
        return [
            'user' => Auth()->user(),
            'portfolios' => Portfolio::byCurrentUser()->get(),
        ];
    }
}
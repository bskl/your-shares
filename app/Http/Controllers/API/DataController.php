<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\User as UserResource;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Get a set of application data.
     *
     * @return array
     */
    public function getData()
    {
        $portfolios = Portfolio::byCurrentUser()->get();

        return [
            'user'       => new UserResource(Auth::user()),
            'portfolios' => PortfolioResource::collection($portfolios),
        ];
    }
}

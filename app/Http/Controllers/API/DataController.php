<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Get a set of application data.
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            'user' => new UserResource(Auth::user()->only('id', 'name', 'email', 'locale', 'role')),
            'portfolios' => PortfolioResource::collection(Auth::user()->portfolios),
        ];
    }
}

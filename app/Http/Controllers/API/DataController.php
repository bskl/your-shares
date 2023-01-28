<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Get a set of application data.
     *
     * @return array{user: \App\Http\Resources\UserResource, portfolios: \Illuminate\Http\Resources\Json\AnonymousResourceCollection}
     */
    public function getData(): array
    {
        return [
            'user' => new UserResource(Auth::user()),
            'portfolios' => PortfolioResource::collection(Auth::user()->portfolios),
        ];
    }
}

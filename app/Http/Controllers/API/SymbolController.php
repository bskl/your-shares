<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Http\Resources\Symbol as SymbolResource;
use App\Models\Portfolio;
use App\Models\Symbol;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class SymbolController extends Controller
{
    /**
     * Get all symbols.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize(Symbol::class);

        $symbols = Symbol::select('id', 'code', 'last_price')->get();

        return SymbolResource::collection($symbols);
    }

    /**
     * Run set symbols command and get portfolios data.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function update(): AnonymousResourceCollection|JsonResponse
    {
        $this->authorize(Symbol::class);

        try {
            Artisan::call('yourshares:set-symbols');

            $portfolios = Portfolio::byCurrentUser()->get();

            return PortfolioResource::collection($portfolios);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_SERVICE_UNAVAILABLE,
                [trans('app.service_error')]
            );
        }
    }
}

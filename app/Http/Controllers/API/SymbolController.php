<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Symbol;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class SymbolController extends Controller
{
    /**
     * Run set symbols command and get portfolios data.
     *
     * @return JsonResponse
     */
    public function getData()
    {
        if (Gate::allows('is_admin')) {
            try {
                Artisan::call('yourshares:set-symbols');
            } catch (\Exception $e) {
                return response()->json(
                    trans('app.service_error'),
                    Response::HTTP_SERVICE_UNAVAILABLE
                );
            }

            return response()->json(
                Portfolio::byCurrentUser()->get()
            );
        }

        return response()->json('', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Get all symbols.
     *
     * @return App\Models\Symbol $symbols
     */
    public function getSymbols()
    {
        $symbols = Symbol::select('id', 'code', 'last_price')
                          ->get();

        return response()->json($symbols);
    }
}

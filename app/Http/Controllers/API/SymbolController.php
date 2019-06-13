<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Symbol;
use Illuminate\Http\Request;
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

        return response()->json(
            trans('app.auth_error'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

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

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        return response()->json($portfolio->only('id', 'name', 'currency', 'commission'));
    }

    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param PortfolioRequest $request
     *
     * @return App\Models\Portfolio $portfolio
     */
    public function store(PortfolioRequest $request)
    {
        $this->authorize(Portfolio::class);

        $order = Portfolio::byCurrentUser()->count();
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['order'] = ++$order;

        try {
            $portfolio = Portfolio::create($data);
            $portfolio->refresh()->load('shares');
    
            return response()->json($portfolio);
        } catch (\Exception $e) {
            return response()->json(
                ['messages' => '', 'errors' => [['portfolio' => trans('app.portfolio.create_error')]]],
                422
            );
        }
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param PortfolioRequest      $request
     * @param Int                   $id
     *
     * @return App\Models\Portfolio $portfolio
     */
    public function update(PortfolioRequest $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        try {
            $portfolio->update($request->all());

            return response()->json($portfolio);
        } catch (\Exception $e) {
            return response()->json(
                ['messages' => '', 'errors' => [['portfolio' => trans('app.portfolio.update_error')]]],
                422
            );
        }
    }

    /**
     * Delete a portfolio.
     *
     * @param Int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        if (Portfolio::byCurrentUser()->count() <= 1) {
            return response()->json(
                trans('app.portfolio.destroy_error'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $portfolio->delete();

            return response()->json();
        } catch (\Exception $e) {
            return response()->json(
                trans('app.portfolio.delete_error'),
                422
            );
        }
    }
}

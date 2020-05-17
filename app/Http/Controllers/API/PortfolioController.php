<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Http\Resources\Portfolio as PortfolioResource;
use App\Models\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Money\Currency;
use Money\Money;

class PortfolioController extends Controller
{
    /**
     * List the portfolios for the auth user.
     *
     * @return \App\Http\Resources\Portfolio $portfolios
     */
    public function index()
    {
        $this->authorize(Portfolio::class);

        $portfolios = Portfolio::byCurrentUser()->get();

        return PortfolioResource::collection($portfolios);
    }

    /**
     * Show the portfolio for the given id.
     *
     * @param int $id
     *
     * @return \App\Http\Resources\Portfolio $portfolio
     */
    public function show(int $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        return new PortfolioResource($portfolio->only('name', 'currency', 'commission'));
    }

    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param PortfolioRequest $request
     *
     * @return \App\Http\Resources\Portfolio $portfolio
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

            return (new PortfolioResource($portfolio))->additional([
                'message' => trans('app.portfolio.create_success'),
            ]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['portfolio' => trans('app.portfolio.create_error')]
            );
        }
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param PortfolioRequest $request
     * @param int              $id
     *
     * @return \App\Http\Resources\Portfolio $portfolio
     */
    public function update(PortfolioRequest $request, int $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        try {
            $portfolio->update($request->all());

            return (new PortfolioResource([]))->additional([
                'message' => trans('app.portfolio.update_success'),
            ]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['portfolio' => trans('app.portfolio.update_error')]
            );
        }
    }

    /**
     * Delete a portfolio instance.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize($portfolio);

        if (Portfolio::byCurrentUser()->count() <= 1) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.portfolio.destroy_error')]
            );
        }

        try {
            $portfolio->delete();

            return (new PortfolioResource([]))->additional([
                'message' => trans('app.portfolio.delete_success'),
            ]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.portfolio.delete_error')]
            );
        }
    }

    /**
     * Get portfolio instance with transactions by type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByType(int $id, string $type)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        return $this->getTransactionsByModelAndType($portfolio, $type);
    }

    /**
     * Get portfolio instance transactions by type and year.
     *
     * @param int    $id
     * @param string $type
     * @param int    $year
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByTypeAndYear(int $id, string $type, int $year)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        $transactionType = $this->getTransactionType($type);
        $attribute = $this->getRawAttribute($transactionType);

        $grouped = $portfolio->transactionsOfType($transactionType['value'])
                             ->with('share.symbol:id,code,last_price')
                             ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                             ->whereYear('date_at', $year)
                             ->orderBy('date_at')
                             ->groupBy('share_id', 'month')
                             ->get()
                             ->groupBy(['share_id', 'month']);

        if (!count($grouped)) {
            return $this->respondSuccess([]);
        }

        $index = 0;

        foreach ($grouped as $key => $transactions) {
            $items[$index]['total'] = $transactionType['condition'] ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($transactionType['condition']) {
                    $items[$index][$month] = decimal_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = money_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
                $items[$index]['item'] = $transaction->first()->share->symbol->code;
                $items[$index]['share_id'] = $transaction->first()->share_id;
                $items[$index]['year'] = $transaction->first()->year;
            }
            $items[$index]['total'] = $transactionType['condition']
                ? decimal_formatter($items[$index]['total'])
                : money_formatter($items[$index]['total']);
            $index++;
        }

        return $this->respondSuccess($items);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionType;
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
     * @return \App\Http\Resources\Portfolio
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
     * @param  \App\Models\Portfolio  $portfolio
     * @return \App\Http\Resources\Portfolio
     */
    public function show(Portfolio $portfolio)
    {
        $this->authorize($portfolio);

        return new PortfolioResource($portfolio->only('name', 'currency', 'commission'));
    }

    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param  \App\Http\Requests\API\PortfolioRequest  $request
     * @return \App\Http\Resources\Portfolio|\Illuminate\Http\JsonResponse
     */
    public function store(PortfolioRequest $request)
    {
        $this->authorize(Portfolio::class);

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['order'] = Portfolio::byCurrentUser()->count() + 1;

        try {
            $portfolio = Portfolio::create($data);
            $portfolio->refresh()->load('shares');

            return new PortfolioResource($portfolio);
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
     * @param  \App\Models\Portfolio  $portfolio
     * @param  \App\Http\Requests\API\PortfolioRequest  $request
     * @return \App\Http\Resources\Portfolio|\Illuminate\Http\JsonResponse
     */
    public function update(Portfolio $portfolio, PortfolioRequest $request)
    {
        $this->authorize($portfolio);

        try {
            $portfolio->update($request->validated());

            return new PortfolioResource([]);
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
     * @param  \App\Models\Portfolio  $portfolio
     * @return \App\Http\Resources\Portfolio|\Illuminate\Http\JsonResponse
     */
    public function destroy(Portfolio $portfolio)
    {
        $this->authorize($portfolio);

        try {
            $portfolio->delete();

            return new PortfolioResource([]);
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
     * @param  \App\Models\Portfolio  $portfolio
     * @param  string  $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByType(Portfolio $portfolio, string $type)
    {
        $this->authorize('view', $portfolio);

        return $this->getTransactionsByModelAndType($portfolio, $type);
    }

    /**
     * Get portfolio instance transactions by type and year.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @param  string  $type
     * @param  int  $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByTypeAndYear(Portfolio $portfolio, string $type, int $year)
    {
        $this->authorize('view', $portfolio);

        $type = TransactionType::fromName(ucfirst($type));
        $transactionType = $this->getTransactionType($type);
        $attribute = $this->getRawAttribute($type);

        $grouped = $portfolio->transactionsOfType($transactionType['value'])
                             ->with('share.symbol:id,code,last_price')
                             ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                             ->whereYear('date_at', $year)
                             ->orderBy('date_at')
                             ->groupBy('share_id', 'month')
                             ->get()
                             ->groupBy(['share_id', 'month']);

        if (! count($grouped)) {
            return $this->respondSuccess([]);
        }

        $index = 0;
        $items = [];

        foreach ($grouped as $transactions) {
            $items[$index]['total'] = $transactionType['condition'] ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($transactionType['condition']) {
                    $items[$index][$month] = decimal_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = $this->formatByIntl($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
                $items[$index]['item'] = $transaction->first()->share->symbol->code;
                $items[$index]['share_id'] = $transaction->first()->share_id;
                $items[$index]['year'] = $transaction->first()->year;
            }
            $items[$index]['total'] = $transactionType['condition']
                ? decimal_formatter($items[$index]['total'])
                : $this->formatByIntl($items[$index]['total']);
            $index++;
        }

        return $this->respondSuccess($items);
    }
}

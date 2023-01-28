<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Support\MoneyManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * List the portfolios for the auth user.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize(Portfolio::class);

        $portfolios = Portfolio::byCurrentUser()->get();

        return PortfolioResource::collection($portfolios);
    }

    /**
     * Show the portfolio for the given id.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return \App\Http\Resources\PortfolioResource
     */
    public function show(Portfolio $portfolio): PortfolioResource
    {
        $this->authorize($portfolio);

        return new PortfolioResource($portfolio->only('name', 'currency', 'commission'));
    }

    /**
     * Create a new portfolio instance for auth user after a valid request.
     *
     * @param  \App\Http\Requests\API\PortfolioRequest  $request
     * @return \App\Http\Resources\PortfolioResource|\Illuminate\Http\JsonResponse
     */
    public function store(PortfolioRequest $request): PortfolioResource|JsonResponse
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
     * @return \App\Http\Resources\PortfolioResource|\Illuminate\Http\JsonResponse
     */
    public function update(Portfolio $portfolio, PortfolioRequest $request): PortfolioResource|JsonResponse
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
     * @return \App\Http\Resources\PortfolioResource|\Illuminate\Http\JsonResponse
     */
    public function destroy(Portfolio $portfolio): PortfolioResource|JsonResponse
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
    public function getTransactionsByType(Portfolio $portfolio, string $type): JsonResponse
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
    public function getTransactionsByTypeAndYear(Portfolio $portfolio, string $type, int $year): JsonResponse
    {
        $this->authorize('view', $portfolio);

        $i = 0;
        $items = [];
        $type = TransactionType::fromName(ucfirst($type));
        $transactionType = $this->getTransactionType($type);
        $attribute = $this->getRawAttribute($type);
        $grouped = $portfolio->transactionsOfType($transactionType['value'])
                             ->with('share.symbol:id,code,last_price')
                             ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                             ->whereYear('date_at', '=', $year)
                             ->orderBy('date_at')
                             ->groupBy('share_id', 'month')
                             ->get()
                             ->groupBy(['share_id', 'month']);

        foreach ($grouped as $transactions) {
            $items[$i]['total'] = $transactionType['condition'] ? 0 : MoneyManager::createMoney();
            foreach ($transactions as $month => $transaction) {
                $transaction = $transaction->first();

                $items[$i] += $transactionType['condition']
                    ? [$month => decimal_formatter($transaction->{$attribute}), 'total' => $items[$i]['total'] + $transaction->lot]
                    : [$month => MoneyManager::formatByIntl($transaction->{$attribute}), 'total' => $items[$i]['total']->add($transaction->{$attribute})];

                $items[$i] += [
                    'item' => $transaction->share->symbol->code,
                    'share_id' => $transaction->share_id,
                    'year' => $transaction->year,
                ];
            }
            $items[$i]['total'] = $transactionType['condition']
                ? decimal_formatter($items[$i]['total'])
                : MoneyManager::formatByIntl($items[$i]['total']);
            $i++;
        }

        return $this->respondSuccess($items);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Money\Currency;
use Money\Money;

class PortfolioController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param int $id
     *
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
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Update given portfolio instance after a valid request.
     *
     * @param PortfolioRequest $request
     * @param int              $id
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
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Delete a portfolio.
     *
     * @param int $id
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
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Get portfolio's buying transactions then list transactions by type.
     *
     * @param int    $id
     *
     * @return JsonResponse
     */
    public function getTransactionsByType($id, $type)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        return $this->getTransactionsByModelAndType($portfolio, $type);
    }

    /**
     * Get portfolio's dividend transactions then list transactions by type and year.
     *
     * @param int    $id
     * @param int    $year
     *
     * @return JsonResponse
     */
    public function getTransactionsByTypeAndYear($id, $type, $year)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        $attribute = $this->getRawAttribute($type);

        $grouped = $portfolio->transactionsOfType([TransactionTypes::getTypeId($type)])
                             ->with('share.symbol:id,code,last_price')
                             ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.' . $attribute . ') AS ' . $attribute)
                             ->whereYear('date_at', $year)
                             ->orderBy('date_at')
                             ->groupBy('share_id', 'month')
                             ->get()
                             ->groupBy(['share_id', 'month']);

        if (!count($grouped)) {
            return response()->json();
        }

        $index = 0;
        $condition = (TransactionTypes::getTypeId($type) === TransactionTypes::BONUS);

        foreach ($grouped as $key => $transactions) {
            $items[$index]['total'] = $condition ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($condition === true) {
                    $items[$index][$month] = $transaction->first()->{$attribute};
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = money_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
                $items[$index]['item'] = $transaction->first()->share->symbol->code;
                $items[$index]['share_id'] = $transaction->first()->share_id;
                $items[$index]['year'] = $transaction->first()->year;
                
            }
            $condition ?: $items[$index]['total'] = money_formatter($items[$index]['total']);
            $index++;
        }

        return response()->json($items);
    }
}

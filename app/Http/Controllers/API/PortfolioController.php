<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
     * Get portfolio transactions by type then list transactions by year.
     *
     * @param int    $id
     * @param string $type
     *
     * @return JsonResponse
     */
    public function getTransactionsOfType($id, $type)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        $transactions = $portfolio->transactions()
                                  ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year')
                                  ->whereType(TransactionTypes::getTypeId($type))
                                  ->orderBy('date_at')
                                  ->get();

        if (!count($transactions)) {
            return response()->json();
        }

        return response()->json($this->listTransactions($transactions, $type));
        

        foreach ($transactions as $key => $transaction) {
            $dividends[$key]['name'] = $transaction->year;
            $dividends[$key]['total'] = Money::TRY(0);
            foreach ($transactions as $item) {
                if ($item->year == $transaction->year) {
                    $dividends[$key]['total'] = $dividends[$key]['total']->add($item->dividend_gain);
                    $dividends[$key][$item->month] = ($dividends[$key][$item->month] ?? Money::TRY(0))->add($item->dividend_gain);
                }
            }
            for ($month = 1; $month <= 12; $month++) {
                $dividends[$key][$month] = $portfolio->convertMoneyToDecimal(
                    $dividends[$key][$month] ?? Money::TRY(0)
                );
            }
            $dividends[$key]['total'] = $portfolio->convertMoneyToDecimal($dividends[$key]['total']);
        }

        return response()->json(collect($dividends)->unique('name')->values()->all());
    }

    /**
     * Get portfolio transactions by type then list transactions by type and year.
     *
     * @param int    $id
     * @param string $type
     * @param int    $year
     *
     * @return JsonResponse
     */
    public function getTransactionsOfTypeAndYear($id, $type, $year)
    {
        $portfolio = Portfolio::findOrFail($id);

        $this->authorize('view', $portfolio);

        $transactions = $portfolio->transactions()
                                  ->with('share.symbol:id,code,last_price')
                                  ->selectRaw('transactions.*, MONTH(date_at) AS month')
                                  ->whereType(TransactionTypes::getTypeId($type))
                                  ->whereYear('date_at', $year)
                                  ->orderBy('date_at')
                                  ->get();

        if (!count($transactions)) {
            return response()->json();
        }

        return response()->json($this->listTransactions($transactions, $type, $year));

        foreach ($transactions as $key => $transaction) {
            $dividends[$key]['name'] = $transaction->share->symbol->code;
            $dividends[$key]['total'] = Money::TRY(0);
            foreach ($transactions as $item) {
                if ($item->share_id == $transaction->share_id) {
                    $dividends[$key]['total'] = $dividends[$key]['total']->add($item->dividend_gain);
                    $dividends[$key][$item->month] = $item->dividend_gain;
                }
            }
            for ($month = 1; $month <= 12; $month++) {
                $dividends[$key][$month] = $portfolio->convertMoneyToDecimal(
                    $dividends[$key][$month] ?? Money::TRY(0)
                );
            }
            $dividends[$key]['total'] = $portfolio->convertMoneyToDecimal($dividends[$key]['total']);
        }

        return response()->json(collect($dividends)->unique('name')->values()->all());
    }

    public function listTransactions($transactions, $type, $year = null)
    {
        switch (TransactionTypes::getTypeId($type)) {
            case TransactionTypes::DIVIDEND:
                $attribute = 'dividend_gain';
                break;
            
            case TransactionTypes::BONUS:
                $attribute = 'lot';
                break;
        }

        $compare = $year ? 'share_id' : 'year';

        foreach ($transactions as $key => $transaction) {
            $dividends[$key]['name'] = $year ? $transaction->share->symbol->code : $transaction->year;
            $dividends[$key]['total'] = TransactionTypes::getTypeId($type) === TransactionTypes::BONUS ? 0 : Money::TRY(0);
            foreach ($transactions as $item) {
                if ($item->{$compare} == $transaction->{$compare}) {
                    if (TransactionTypes::getTypeId($type) === TransactionTypes::BONUS) {
                        $dividends[$key]['total'] = $dividends[$key]['total'] + $item->{$attribute};
                    } else {
                        $dividends[$key]['total'] = $dividends[$key]['total']->add($item->{$attribute});
                    }
                    
                    if ($year) {
                        $dividends[$key][$item->month] = $item->{$attribute};
                    } else {
                        if (TransactionTypes::getTypeId($type) === TransactionTypes::BONUS) {
                            $dividends[$key][$item->month] = ($dividends[$key][$item->month] ?? 0) + $item->{$attribute};
                        } else {
                            $dividends[$key][$item->month] = ($dividends[$key][$item->month] ?? Money::TRY(0))->add($item->{$attribute});
                        }
                    }
                }
            }
            for ($month = 1; $month <= 12; $month++) {
                if (TransactionTypes::getTypeId($type) !== TransactionTypes::BONUS) {
                    $dividends[$key][$month] = convert_money_to_decimal(
                        $dividends[$key][$month] ?? Money::TRY(0)
                    );
                } else {
                    $dividends[$key][$month] = $dividends[$key][$month] ?? 0;
                }
            }
            if (TransactionTypes::getTypeId($type) !== TransactionTypes::BONUS) {
                $dividends[$key]['total'] = convert_money_to_decimal($dividends[$key]['total']);
            }
        }

        return collect($dividends)->unique('name')->values()->all();
    }
}

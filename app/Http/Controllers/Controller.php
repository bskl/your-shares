<?php

namespace App\Http\Controllers;

use App\Enums\TransactionTypes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Money\Currency;
use Money\Money;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get transactions by model and type.
     *
     * @param mixed  $model
     * @param string $type
     *
     * @return JsonResponse
     */
    public function getTransactionsByModelAndType($model, $type)
    {
        $attribute = $this->getRawAttribute($type);

        $grouped = $model->transactionsOfType([TransactionTypes::getTypeId($type)])
                         ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                         ->orderBy('date_at')
                         ->groupBy('year', 'month')
                         ->get()
                         ->groupBy(['year', 'month']);

        if (!count($grouped)) {
            return response()->json();
        }

        $index = 0;
        $condition = (TransactionTypes::getTypeId($type) === TransactionTypes::BONUS);

        foreach ($grouped as $key => $transactions) {
            $items[$index]['item'] = $key;
            $items[$index]['total'] = $condition ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($condition === true) {
                    $items[$index][$month] = $transaction->first()->{$attribute};
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = money_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
            }
            $condition ?: $items[$index]['total'] = money_formatter($items[$index]['total']);
            $index++;
        }

        return response()->json($items);
    }

    /**
     * Get attribute by type for use raw statemnet.
     *
     * @param string $type
     *
     * @return string
     */
    public function getRawAttribute($type)
    {
        switch (TransactionTypes::getTypeId($type)) {
            case TransactionTypes::BUYING:
            case TransactionTypes::SALE:
                $attribute = 'amount';
                break;

            case TransactionTypes::DIVIDEND:
                $attribute = 'dividend_gain';
                break;

            case TransactionTypes::BONUS:
                $attribute = 'lot';
                break;
        }

        return $attribute;
    }
}

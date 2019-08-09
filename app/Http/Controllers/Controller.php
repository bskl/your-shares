<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
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
        $transactionType = TransactionType::getInstance(TransactionType::getValue(ucfirst($type)));
        $attribute = $this->getRawAttribute($transactionType);

        $grouped = $model->transactionsOfType([$transactionType->value])
                         ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                         ->orderBy('date_at')
                         ->groupBy('year', 'month')
                         ->get()
                         ->groupBy(['year', 'month']);

        if (!count($grouped)) {
            return response()->json();
        }

        $index = 0;
        $condition = $transactionType->is(TransactionType::Bonus);

        foreach ($grouped as $key => $transactions) {
            $items[$index]['item'] = $key;
            $items[$index]['total'] = $condition ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($condition === true) {
                    $items[$index][$month] = decimal_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = money_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
            }
            $items[$index]['total'] = $condition ? decimal_formatter($items[$index]['total']) : money_formatter($items[$index]['total']);
            $index++;
        }

        return response()->json($items);
    }

    /**
     * Get attribute by type for use raw statement.
     *
     * @param string $type
     *
     * @return string
     */
    public function getRawAttribute(TransactionType $transactionType)
    {
        switch ($transactionType->value) {
            case TransactionType::Buying:
                $attribute = 'amount';
                break;

            case TransactionType::Sale:
                $attribute = 'amount';
                break;

            case TransactionType::Dividend:
                $attribute = 'dividend_gain';
                break;

            case TransactionType::Bonus:
                $attribute = 'lot';
                break;
        }

        return $attribute;
    }
}

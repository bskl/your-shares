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
        $transactionType = $this->getTransactionType($type);
        $attribute = $this->getRawAttribute($transactionType);

        $grouped = $model->transactionsOfType($transactionType['value'])
                         ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                         ->orderBy('date_at')
                         ->groupBy('year', 'month')
                         ->get()
                         ->groupBy(['year', 'month']);

        if (!count($grouped)) {
            return response()->json();
        }

        $index = 0;

        foreach ($grouped as $key => $transactions) {
            $items[$index]['item'] = $key;
            $items[$index]['total'] = $transactionType['condition'] ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                if ($transactionType['condition']) {
                    $items[$index][$month] = decimal_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->first()->lot;
                } else {
                    $items[$index][$month] = money_formatter($transaction->first()->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->first()->{$attribute});
                }
            }
            $items[$index]['total'] = $transactionType['condition'] ? decimal_formatter($items[$index]['total']) : money_formatter($items[$index]['total']);
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
    public function getRawAttribute($transactionType)
    {
        switch ($transactionType['value'][0]) {
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

            case TransactionType::Rights:
                $attribute = 'amount';
                break;
        }

        return $attribute;
    }

    /**
     * Get transaction type attributes by type.
     *
     * @param string $type
     *
     * @return void
     */
    public function getTransactionType($value)
    {
        $type = TransactionType::getInstance(TransactionType::getValue(ucfirst($value)));

        $transactionType = collect();
        $transactionType->put('value', 
            $type->is(TransactionType::Buying)
                ? [TransactionType::Buying, TransactionType::Rights]
                : [$type->value]
        );
        $transactionType->put('condition', $type->is(TransactionType::Bonus));

        return $transactionType->all();
    }
}

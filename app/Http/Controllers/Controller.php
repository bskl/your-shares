<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Money\Currency;
use Money\Money;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return message and response success with JSON.
     *
     * @param array  $data
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess(array $data = [], $message = '') : JsonResponse
    {
        return response()->json([
            'data'    => $data,
            'message' => $message,
        ], Response::HTTP_OK);
    }

    /**
     * Return message and response error with JSON.
     *
     * @param int    $status
     * @param array  $errors
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondError(int $status, array $errors, string $message = '') : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors'  => [$errors],
        ], $status);
    }

    /**
     * Get transactions by model and type.
     *
     * @param mixed  $model
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByModelAndType(Model $model, string $type) : JsonResponse
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
            return $this->respondSuccess([]);
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
            $items[$index]['total'] = $transactionType['condition']
                ? decimal_formatter($items[$index]['total'])
                : money_formatter($items[$index]['total']);
            $index++;
        }

        return $this->respondSuccess($items);
    }

    /**
     * Get attribute by type for use raw statement.
     *
     * @param array $transactionType
     *
     * @return string
     */
    public function getRawAttribute(array $transactionType) : string
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
     * @return array
     */
    public function getTransactionType(string $type) : array
    {
        $type = TransactionType::getInstance(TransactionType::getValue(ucfirst($type)));

        $transactionType = collect();
        $transactionType->put(
            'value',
            $type->is(TransactionType::Buying)
                ? [TransactionType::Buying, TransactionType::Rights]
                : [$type->value]
        );
        $transactionType->put('condition', $type->is(TransactionType::Bonus));

        return $transactionType->all();
    }
}

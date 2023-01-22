<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Portfolio;
use App\Models\Share;
use App\Traits\MoneyManager;
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
    use AuthorizesRequests, DispatchesJobs, MoneyManager, ValidatesRequests;

    /**
     * Return message and response success with JSON.
     *
     * @param  array  $data
     * @param  string  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess(array $data = [], $message = '', int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * Return message and response error with JSON.
     *
     * @param  int  $status
     * @param  array  $errors
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondError(int $status, array $errors, string $message = ''): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => [$errors],
        ], $status);
    }

    /**
     * Get transactions by model and type.
     *
     * @param  \App\Models\Portfolio|\App\Models\Share  $model
     * @param  string  $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionsByModelAndType(Portfolio|Share $model, string $type): JsonResponse
    {
        $type = TransactionType::fromName(ucfirst($type));
        $transactionType = $this->getTransactionType($type);
        $attribute = $this->getRawAttribute($type);

        $grouped = $model->transactionsOfType($transactionType['value'])
                         ->selectRaw('transactions.*, MONTH(date_at) AS month, YEAR(date_at) AS year, SUM(transactions.'.$attribute.') AS '.$attribute)
                         ->orderBy('date_at')
                         ->groupBy('year', 'month')
                         ->get()
                         ->groupBy(['year', 'month']);

        if (! count($grouped)) {
            return $this->respondSuccess([]);
        }

        $index = 0;
        $items = [];

        foreach ($grouped as $key => $transactions) {
            $items[$index]['item'] = $key;
            $items[$index]['total'] = $transactionType['condition'] ? 0 : new Money(0, new Currency(config('app.currency')));
            foreach ($transactions as $month => $transaction) {
                $transaction = $transaction->first();
                if ($transactionType['condition']) {
                    $items[$index][$month] = decimal_formatter($transaction->{$attribute});
                    $items[$index]['total'] = $items[$index]['total'] + $transaction->lot;
                } else {
                    $items[$index][$month] = $this->formatByIntl($transaction->{$attribute});
                    $items[$index]['total'] = $items[$index]['total']->add($transaction->{$attribute});
                }
            }
            $items[$index]['total'] = $transactionType['condition']
                ? decimal_formatter($items[$index]['total'])
                : $this->formatByIntl($items[$index]['total']);
            $index++;
        }

        return $this->respondSuccess($items);
    }

    /**
     * Get attribute by type for use raw statement.
     *
     * @param  \App\Enums\TransactionType  $type
     * @return string
     */
    public function getRawAttribute(TransactionType $type): string
    {
        return match ($type) {
            TransactionType::Buying || TransactionType::Sale || TransactionType::Rights => 'amount',
            TransactionType::Dividend => 'dividend_gain',
            TransactionType::Bonus => 'lot',
            default => 'amount',
        };
    }

    /**
     * Get transaction type attributes by type.
     *
     * @param  \App\Enums\TransactionType  $type
     * @return array<string, array|bool>
     */
    public function getTransactionType(TransactionType $type): array
    {
        return [
            'value' => $type->is(TransactionType::Buying)
                ? [TransactionType::Buying, TransactionType::Rights, TransactionType::PublicOffering]
                : [$type],
            'condition' => $type->is(TransactionType::Bonus),
        ];
    }
}

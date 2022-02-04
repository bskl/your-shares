<?php

namespace App\Casts;

use App\Traits\MoneyManager;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Money as PhpMoney;

class Money implements CastsAttributes
{
    use MoneyManager;

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \Money\Money
     */
    public function get($model, $key, $value, $attributes): PhpMoney
    {
        return $this->createMoney($value ?? '0');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes): string
    {
        return $this->parseByDecimal($value)->getAmount();
    }

    /**
     * Serialize the attribute when converting the model to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \Money\Money  $value
     * @param  array  $attributes
     * @return string
     */
    public function serialize($model, string $key, $value, array $attributes): string
    {
        return $this->formatByIntl($value);
    }
}

<?php

namespace App\Casts;

use App\Support\MoneyManager;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Money\Money;

/**
 * @implements CastsAttributes<\Money\Money, string>
 */
class MoneyCast implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string|null  $value
     * @psalm-param numeric-string|null  $value
     * @param  array  $attributes
     * @return \Money\Money
     */
    public function get($model, string $key, $value, array $attributes): Money
    {
        return MoneyManager::createMoney($value ?? '0');
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \Money\Money|null  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return is_null($value) ? '0' : MoneyManager::parseByDecimal($value)->getAmount();
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
        return MoneyManager::formatByIntl($value);
    }
}

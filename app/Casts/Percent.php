<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Support\Str;
use NumberFormatter;

/**
 * @implements CastsAttributes<string|null, string>
 */
class Percent implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string|null  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes): ?string
    {
        return $value;
    }

    /**
     * Convert value to float. ie. '1.000,55' becomes '1000.55', '0,1234' becomes '0.1234',
     * '1.325.125,54' becomes '1325125.54', '1,325,125.54' becomes '1325125.54'.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        $value = preg_replace('/\.(?=.*\.)/', '', Str::replace(',', '.', $value));

        return (is_null($value) || $value === '0') ? '0' : bcdiv($value, '100', 4);
    }

    /**
     * Serialize the attribute when converting the model to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        $percentFormatter = new NumberFormatter(config('app.locale'), NumberFormatter::PERCENT);
        $percentFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        return $percentFormatter->format($value);
    }
}

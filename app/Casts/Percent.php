<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use NumberFormatter;

class Percent implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     * Convert value to float. ie. '1.000,55' becomes '1000.55', '0,1234' becomes '0.1234',
     * '1.325.125,54' becomes '1325125.54', '1,325,125.54' becomes '1325125.54'.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return (float) preg_replace('/\.(?=.*\.)/', '', str_replace(',', '.', $value)) / 100;
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
        $percentFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $percentFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);

        return $percentFormatter->format($value);
    }
}

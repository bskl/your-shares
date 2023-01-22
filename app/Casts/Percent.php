<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use NumberFormatter;

class Percent implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * {@inheritdoc}
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * Convert value to float. ie. '1.000,55' becomes '1000.55', '0,1234' becomes '0.1234',
     * '1.325.125,54' becomes '1325125.54', '1,325,125.54' becomes '1325125.54'.
     *
     * {@inheritdoc}
     */
    public function set($model, string $key, $value, array $attributes)
    {
        $value = preg_replace('/\.(?=.*\.)/', '', str_replace(',', '.', $value));

        return (is_null($value) || $value === '0.00') ? '0.00' : (int) $value / 100;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        $percentFormatter = new NumberFormatter(config('app.locale'), NumberFormatter::PERCENT);
        $percentFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        return $percentFormatter->format($value);
    }
}

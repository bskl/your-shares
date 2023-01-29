<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use NumberFormatter;

class Decimal implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Create a new cast class instance.
     *
     * @param  int  $digits
     * @return void
     */
    public function __construct(
        protected int $digits = 3
    ) {
        $this->digits = $digits;
    }

    /**
     * {@inheritdoc}
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($model, string $key, $value, array $attributes): string
    {
        $decimalFormatter = new NumberFormatter(config('app.locale'), NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $this->digits);

        return $decimalFormatter->format($value) ?: '';
    }
}

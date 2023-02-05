<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use NumberFormatter;

/**
 * @implements CastsAttributes<string, string>
 */
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
        //
    }

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get($model, string $key, $value, array $attributes): ?string
    {
        return $value;
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        return $value;
    }

    /**
     * Serialize the attribute when converting the model to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function serialize($model, string $key, $value, array $attributes): string
    {
        $decimalFormatter = new NumberFormatter(config('app.locale'), NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $this->digits);

        return $decimalFormatter->format($value) ?: '';
    }
}

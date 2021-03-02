<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use NumberFormatter;

class Decimal implements CastsAttributes
{
    /**
     * The minimum fraction digits.
     *
     * @var int
     */
    protected $digits;

    /**
     * Create a new cast class instance.
     *
     * @param  int  $digits
     * @return void
     */
    public function __construct($digits = 3)
    {
        $this->digits = $digits;
    }

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
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
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
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        $decimalFormatter = new NumberFormatter(config('app.locale'), NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $this->digits);

        return $decimalFormatter->format($value);
    }
}

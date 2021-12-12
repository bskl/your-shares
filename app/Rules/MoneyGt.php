<?php

namespace App\Rules;

use App\Traits\MoneyManager;
use Illuminate\Contracts\Validation\Rule;

class MoneyGt implements Rule
{
    use MoneyManager;

    /**
     * Compared value.
     *
     * @var \Money\Money|string
     */
    protected $other;

    /**
     * Create a new rule instance.
     *
     * @param  \Money\Money|string  $parameters
     * @return void
     */
    public function __construct($parameters)
    {
        $this->other = $parameters;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  int|string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $money = $this->parseByDecimal($value);
        $this->other = $this->parseByDecimal($this->other);

        return $money->greaterThan($this->other);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.money_gt', ['value' => $this->formatByIntl($this->other)]);
    }
}

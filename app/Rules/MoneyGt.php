<?php

namespace App\Rules;

use App\Support\MoneyManager;
use Illuminate\Contracts\Validation\Rule;

class MoneyGt implements Rule
{
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
        $money = MoneyManager::parseByDecimal($value);
        $this->other = MoneyManager::parseByDecimal($this->other);

        return $money->greaterThan($this->other);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.money_gt', ['value' => MoneyManager::formatByIntl($this->other)]);
    }
}

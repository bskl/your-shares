<?php

namespace App\Rules;

use App\Support\MoneyManager;
use Illuminate\Contracts\Validation\Rule;
use Money\Money;

class MoneyGt implements Rule
{
    /**
     * Compared value.
     *
     * @var \Money\Money
     */
    protected $other;

    /**
     * Create a new rule instance.
     *
     * @param  \Money\Money|string  $parameters
     * @return void
     */
    public function __construct(Money|string $parameters)
    {
        $this->other = MoneyManager::parseByDecimal($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value): bool
    {
        $money = MoneyManager::parseByDecimal($value);

        return $money->greaterThan($this->other);
    }

    /**
     * {@inheritdoc}
     */
    public function message(): string
    {
        return trans('validation.money_gt', ['value' => MoneyManager::formatByIntl($this->other)]);
    }
}

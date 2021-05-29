<?php

namespace App\Traits;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;

trait MoneyManager
{
    /**
     * Create Money object with given value.
     * Given value must be integer(ish) value. e.g. 100, '100', '100.00' is
     * become 1 TRY.
     *
     * @param  \Money\Money|string  $value
     * @return \Money\Money
     */
    public function createMoney($value = '0'): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        return new Money($value, new Currency(config('app.currency')));
    }

    /**
     * Parse given value with intl localized decimal parser to create Money object.
     *
     * @param  \Money\Money|string  $value
     * @return \Money\Money
     */
    public function parseByIntlLocalizedDecimal($value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
        $moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);

        return $moneyParser->parse($value, new Currency(config('app.currency')));
    }

    /**
     * Parse given value with decimal parser to create Money object.
     *
     * @param  \Money\Money|string|int  $value
     * @return \Money\Money
     */
    public function parseByDecimal($value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse((string) $value, new Currency(config('app.currency')));
    }

    /**
     * Format given money with intl money formatter.
     *
     * @param  \Money\Money  $money
     * @return string
     */
    public function formatByIntl(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Get trend for money attributes.
     *
     * @param  \Money\Money  $money
     * @return int
     */
    public function getTrend(Money $money): int
    {
        return $money->isPositive() ? 1 : ($money->isNegative() ? -1 : 0);
    }
}

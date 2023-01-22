<?php

namespace App\Support;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;

final class MoneyManager
{
    /**
     * Create Money object with given value.
     * Given value must be integer(ish) value. e.g. 100, '100', '100.00' is
     * become 1 TRY.
     *
     * @param  int|string  $value
     *
     * @psalm-param int|numeric-string  $value
     *
     * @return \Money\Money
     */
    public static function createMoney(int|string $value = '0'): Money
    {
        return new Money($value, new Currency(config('app.currency')));
    }

    /**
     * Parse given value with intl localized decimal parser to create Money object.
     *
     * @param  \Money\Money|string  $value
     * @return \Money\Money
     */
    public static function parseByIntlLocalizedDecimal(Money|string $value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->currentLocale(), \NumberFormatter::DECIMAL);
        $moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);

        return $moneyParser->parse($value, new Currency(config('app.currency')));
    }

    /**
     * Parse given value with decimal parser to create Money object.
     *
     * @param  \Money\Money|string  $value
     * @return \Money\Money
     */
    public static function parseByDecimal(Money|string $value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse($value, new Currency(config('app.currency')));
    }

    /**
     * Format given money with intl money formatter.
     *
     * @param  \Money\Money  $money
     * @return string
     */
    public static function formatByIntl(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->currentLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Format given money with decimal formatter.
     *
     * @param  \Money\Money  $money
     * @return string
     */
    public static function formatByDecimal(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Get trend for money attributes.
     *
     * @param  \Money\Money  $money
     * @return int
     */
    public static function getTrend(Money $money): int
    {
        return $money->isPositive() ? 1 : ($money->isNegative() ? -1 : 0);
    }
}

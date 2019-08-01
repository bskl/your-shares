<?php

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

/**
 * Convert Money object to intl money format.
 *
 * @param Money\Money $money
 *
 * @return string
 */
function money_formatter(Money $money)
{
    $currencies = new ISOCurrencies();

    $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::CURRENCY);
    $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

    return $moneyFormatter->format($money);
}

/**
 * Convert number to percent format.
 *
 * @param int $value
 *
 * @return string
 */
function percent_formatter($value)
{
    $percentFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::PERCENT);
    $percentFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);

    return $percentFormatter->format($value);
}

/**
 * Convert number to decimal format.
 *
 * @param int $value
 *
 * @return string
 */
function decimal_formatter($value)
{
    $decimalFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
    $decimalFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 3);

    return $decimalFormatter->format($value);
}

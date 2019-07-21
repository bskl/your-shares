<?php

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

/**
 * Convert Money object to decimal.
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
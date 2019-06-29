<?php

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

/**
 * Convert Money object to decimal.
 *
 * @param Money\Money $money
 *
 * @return string
 */
function convert_money_to_decimal(Money $money)
{
    $currencies = new ISOCurrencies();

    $moneyFormatter = new DecimalMoneyFormatter($currencies);

    return $moneyFormatter->format($money);
}
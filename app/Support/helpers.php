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
function money_formatter(Money $money): string
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
function percent_formatter($value): string
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
function decimal_formatter($value): string
{
    $decimalFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
    $decimalFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 3);

    return $decimalFormatter->format($value);
}

/**
 * Format given values by number formatter.
 *
 * @param string|array $values
 *
 * @return string|array
 */
function format_decimal_symbol($values)
{
    $formatted = [];

    foreach (Arr::wrap($values) as $value) {
        $dotPos = strrpos($value, '.');
        $commaPos = strrpos($value, ',');
        $separatorPos = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
                        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$separatorPos || (strlen($value) - $separatorPos) > 4) {
            $formatted[] = strval($value);
            continue;
        }

        //$formatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
        //$symbol = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $symbol = '.';

        $formatted[] = substr_replace($value, $symbol, $separatorPos, 1);
    }

    return is_array($values) ? $formatted : $formatted[0];
}

/**
 * Convert value to float.
 *
 * @param mixed $value
 *
 * @return float
 */
function to_float($value): float
{
    if (strstr($value, ',')) {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
    }

    return floatval($value);
}

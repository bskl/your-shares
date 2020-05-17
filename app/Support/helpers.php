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
        $value = strval($value);
        $dotPos = strrpos($value, '.');
        $commaPos = strrpos($value, ',');
        $separatorPos = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
                        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$separatorPos || (strlen($value) - $separatorPos) > 3) {
            $formatted[] = strval($value);
            continue;
        }

        $formatter = new \NumberFormatter('tr_TR', \NumberFormatter::DECIMAL);
        $symbol = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

        $formatted[] = substr_replace($value, $symbol, $separatorPos, 1);
    }

    return is_array($values) ? $formatted : $formatted[0];
}

/**
 * Convert value to decimal.
 * [1.000, '0,15', '1.234,00'] becomes ['1000', '0.15', '1234']
 * 8.650.45 becomes '8650.45'
 *
 * @param string|array $values
 *
 * @return string|array
 */
function to_decimal($values)
{
    $formatted = [];

    foreach (Arr::wrap($values) as $value) {
        $value = strval($value);
        $dotPos = strrpos($value, '.');
        $commaPos = strrpos($value, ',');
        $separatorPos = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
                        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$separatorPos || (strlen($value) - $separatorPos) > 3) {
            $formatted[] = preg_replace('/\D/', '', $value);
            continue;
        }

        $digits =  preg_replace('/\D/', '', substr($value, 0, $separatorPos));
        $fraction = preg_replace('/\D/', '', substr($value, $separatorPos));

        $formatted[] = filled($fraction) ? $digits.'.'.$fraction : $digits;
    }

    return is_array($values) ? $formatted : $formatted[0];
}

/**
 * Convert value to float. ie. 1.000,55 becomes 1000.55 or 0,1234 becomes 0.1234
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

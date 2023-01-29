<?php

/**
 * Convert number to decimal format.
 *
 * @param  int  $value
 * @return string
 */
function decimal_formatter(int $value): string
{
    $decimalFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
    $decimalFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 3);

    return $decimalFormatter->format($value) ?: '';
}

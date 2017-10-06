<?php

namespace App\Models;

use Money\Money;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  Money\Money  $money
     * @param  string  $locale
     * @return string
     */
    public function getFormattedAmount(Money $money)
    {
        $currencies = new ISOCurrencies();
        
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    public function decimalParser($value)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($value, 'TRY');

        return $money->getAmount();
    }
}

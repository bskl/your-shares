<?php

namespace App\Models;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
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
    public function getFormattedAmount(\Money\Money $money)
    {
        $currencies = new ISOCurrencies();
        
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }
}

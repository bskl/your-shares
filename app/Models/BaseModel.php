<?php

namespace App\Models;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
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
    public function getFormattedAmount(\Money\Money $money, string $locale)
    {
        $currencies = new ISOCurrencies();
        
        $numberFormatter = new \NumberFormatter('tr_TR', \NumberFormatter::CURRENCY);
        
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}

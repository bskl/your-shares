<?php

namespace App\Models;

use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [];

    /**
     * Get money object for given attribute from the model.
     *
     * @param  string  $key
     * @return Money
     */
    public function getMoneyAttribute($key)
    {
        return new Money($this->attributes[$key], new Currency('TRY'));
    }

    /**
     * Convert Money object to decimal.
     *
     * @param  Money\Money  $money
     * @return string
     */
    public function convertMoneyToDecimal(Money $money)
    {
        $currencies = new ISOCurrencies();
        
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setMoneyAttribute($key, $value)
    {
        if ($value instanceof Money) {
            $money = $value;
        } else {
            $currencies = new ISOCurrencies();
            
            $moneyParser = new DecimalMoneyParser($currencies);
    
            $money = $moneyParser->parse($value, 'TRY');
        }

        $this->attributes[$key] = $money->getAmount();
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->money)) {           
            return $this->getMoneyAttribute($key);
        }

        return parent::__get($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->money)) {
            return $this->setMoneyAttribute($key, $value);
        }
    }
}

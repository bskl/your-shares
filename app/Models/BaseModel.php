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
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [];

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  Money\Money  $money
     * @param  string  $locale
     * @return string
     */
    public function getMoneyFormattedAttribute(Money $money)
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

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getMoneyAttribute($key)
    {
        return new Money($this->attributes[$key], new Currency('TRY'));
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

        if (ends_with($key, '_formatted')) {
            if (array_key_exists($key, $this->appends)) {
                $key = str_replace_last('_formatted', '', $key);

                return $this->getMoneyFormattedAttribute($key);
            }
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

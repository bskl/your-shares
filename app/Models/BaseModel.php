<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

abstract class BaseModel extends Model
{
    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [];

    /**
     * Convert the model's money attributes to decimal in attributes.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->money as $key) {
            $attributes[$key] = $this->convertMoneyToDecimal($this->$key);
        }

        return $attributes;
    }

    /**
     * Convert Money object to decimal.
     *
     * @param Money\Money $money
     *
     * @return string
     */
    public function convertMoneyToDecimal(Money $money)
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Get money object for given attribute from the model.
     *
     * @param string $key
     *
     * @return Money
     */
    public function getMoneyAttribute($key)
    {
        return new Money($this->attributes[$key], new Currency('TRY'));
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
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

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!$key) {
            return;
        }

        if (in_array($key, $this->money)) {
            return $this->getMoneyAttribute($key);
        }

        return parent::__get($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        if ($value && in_array($key, $this->money)) {
            return $this->setMoneyAttribute($key, $value);
        }

        return parent::__set($key, $value);
    }
}

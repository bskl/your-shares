<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;

abstract class BaseModel extends Model
{
    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [];

    /**
     * The attributes that are format percentages.
     *
     * @var array
     */
    protected $percent = [];

    /**
     * The attributes that are format decimal.
     *
     * @var array
     */
    protected $decimal = [];

    /**
     * Convert the model's money attributes to decimal in attributes.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->money as $key) {
            $attributes[$key] = money_formatter($this->$key);
        }

        foreach ($this->percent as $key) {
            $attributes[$key] = percent_formatter($this->$key);
        }

        foreach ($this->decimal as $key) {
            $attributes[$key] = decimal_formatter($this->$key);
        }

        return $attributes;
    }

    /**
     * Move the item to the end of the collection.
     *
     * @param Illuminate\Database\Eloquent\Collection $collection
     * @param int|string                              $key
     * @param int|string                              $value
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function moveItemToEnd($collection, $key, $value)
    {
        foreach ($collection as $id => $item) {
            if ($collection[$id]->$key == $value) {
                $spliced = $collection->splice($id, 1)->first();
                $collection->push($spliced);
            }
        }

        return $collection;
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
        return new Money($this->attributes[$key], new Currency(config('app.currency')));
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
            $numberFormatter = new \NumberFormatter('tr_TR', \NumberFormatter::DECIMAL);
            $moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);

            //$moneyParser = new DecimalMoneyParser($currencies);

            $money = $moneyParser->parse($value, config('app.currency'));
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

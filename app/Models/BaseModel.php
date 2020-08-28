<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\IntlMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;

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
     * Get an attribute from the model then format some attributes.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->money)) {
            $value = $this->getMoneyAttribute($value);
        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if (is_null($value)) {
            return parent::setAttribute($key, $value);
        }

        if (in_array($key, $this->money)) {
            $value = $this->setMoneyAttribute($value);
        }

        if (in_array($key, $this->percent)) {
            $value = $this->setPercentAttribute($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Convert the model's attributes to an array then format some attributes.
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        if (empty($attributes)) {
            return $attributes;
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->money)) {
                $attributes[$key] = money_formatter($this->$key);
            }

            if (in_array($key, $this->percent)) {
                $attributes[$key] = percent_formatter($this->$key);
            }

            if (in_array($key, $this->decimal)) {
                $attributes[$key] = decimal_formatter($this->$key);
            }
        }

        return $attributes;
    }

    /**
     * Get money object for given attribute from the model.
     *
     * @param string $value
     *
     * @return \Money\Money
     */
    public function getMoneyAttribute($value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        return new Money($value, new Currency(config('app.currency')));
    }

    /**
     * Set a given attribute on the model.
     *
     * @param \Money\Money|string|int $value
     *
     * @return string
     */
    public function setMoneyAttribute($value): string
    {
        if ($value instanceof Money) {
            return $value->getAmount();
        }

        $currencies = new ISOCurrencies();
        //$numberFormatter = new \NumberFormatter('tr_TR', \NumberFormatter::DECIMAL);
        //$moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);
        $moneyParser = new DecimalMoneyParser($currencies);
        $money = $moneyParser->parse(to_decimal($value), new Currency(config('app.currency')));

        return $money->getAmount();
    }

    /**
     * Format given money with intl money formatter.
     *
     * @param \Money\Money  $money
     *
     * @return string
     */
    public function formatByIntl(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Format given money with decimal money formatter.
     *
     * @param \Money\Money  $money
     *
     * @return string
     */
    public function formatByDecimal(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param mixed $value
     *
     * @return float
     */
    public function setPercentAttribute($value): float
    {
        return to_float($value) / 100;
    }

    /**
     * Move the item to the end of the collection by value for the given key.
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param int|string                               $key
     * @param int|string                               $value
     *
     * @return \Illuminate\Database\Eloquent\Collection
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
}

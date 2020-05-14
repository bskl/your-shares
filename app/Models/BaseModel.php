<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
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
     * The attributes that are encryptable.
     *
     * @var array
     */
    protected $encryptable = [];

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

        if (in_array($key, $this->encryptable)) {
            $value = $this->decrypt($value);
        }

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

        if (in_array($key, $this->encryptable)) {
            $value = $this->encrypt($value);
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
        //$numberFormatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::DECIMAL);
        //$moneyParser = new IntlLocalizedDecimalParser($numberFormatter, $currencies);
        $moneyParser = new DecimalMoneyParser($currencies);
        $money = $moneyParser->parse(format_decimal_symbol($value), new Currency(config('app.currency')));

        return $money->getAmount();
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
     * Encrypt the given value.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function encrypt($value): string
    {
        try {
            $value = Crypt::encrypt($value);
        } catch (Exception $e) {
        }

        return $value;
    }

    /**
     * Decrypt the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function decrypt($value)
    {
        try {
            $value = Crypt::decrypt($value);
        } catch (Exception $e) {
        }

        return $value;
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

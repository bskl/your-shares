<?php

namespace App\Models;

use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Carbon\Carbon;

class Transaction extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'share_id', 'type', 'date', 'lot', 'price', 'amount', 'commission', 'commission_price', 'average', 'sale_gain',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'price', 'amount', 'commission_price', 'average', 'sale_gain',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the portfolio symbol.
     */
    public function share()
    {
        return $this->belongsTo('App\Models\Share');
    }

    /**
     * Set the date attribute.
     */
    public function setDateAttribute($date)
    {
        if ($date) {
            $this->attributes['date'] = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        }
    }

    public function setAmountAttribute($value)
    {
        if ($value instanceof Money) {
            $money = $value;
        } else {
            $currencies = new ISOCurrencies();
            
            $moneyParser = new DecimalMoneyParser($currencies);
    
            $money = $moneyParser->parse($value, 'TRY');
        }

        $this->attributes['amount'] = $money->getAmount();
    }

    public function setPriceAttribute($value)
    {
        if ($value instanceof Money) {
            $money = $value;
        } else {
            $currencies = new ISOCurrencies();
            
            $moneyParser = new DecimalMoneyParser($currencies);
    
            $money = $moneyParser->parse($value, 'TRY');
        }

        $this->attributes['price'] = $money->getAmount();
    }
}

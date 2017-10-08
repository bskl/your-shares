<?php

namespace App\Models;

use Money\Money;
use Money\Currency;
use Carbon\Carbon;

class Transaction extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'share_id', 'type', 'date', 'share', 'price', 'amount', 'commission', 'commission_price', 'average', 'sale_gain',
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
    public function portfolioSymbol()
    {
        return $this->belongsTo('App\Models\PortfolioSymbol');
    }

    /**
     * Set the date attribute.
     */
    public function setDateAttribute($date)
    {
        if ($date) {
            $this->attributes['date'] = Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d');
        }
    }

    /**
     * Set the price attribute.
     */
    public function setPriceAttribute($value)
    {
		$this->attributes['price'] = $this->decimalParser($value);
    }

    /**
     * Get the price attribute with money object.
     */
    public function getPriceAttribute()
    {
        return new Money($this->attributes['price'], new Currency('TRY'));
    }

    /**
     * Get the price attribute with money object.
     */
    public function getTotalPriceAttribute()
    {
        return $this->price->multiply($this->share);
    }
}

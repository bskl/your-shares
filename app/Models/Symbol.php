<?php

namespace App\Models;

use Money\Money;
use Money\Currency;

class Symbol extends BaseModel
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
        'code', 'name', 'title', 'trend', 'last_price', 'rate_of_change', 'session_time',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'last_price_formatted',
    ];

    /**
     * The portfolios that belong to the symbol.
     */
    public function portfolios()
    {
        return $this->belongsToMany('App\Models\Portfolio');
    }

    /**
     * Get the last_price attribute with money object.
     */
    public function getLastPriceAttribute()
    {
        return new Money($this->attributes['last_price'], new Currency('TRY'));
    }

    /**
     * Get the last_price attribute with the given formatted money.
     */
    public function getLastPriceFormattedAttribute()
    {
        return $this->getFormattedAmount($this->last_price);
    }

    /**
     * Get the rate_of_change attribute with divided 100.
     */
    public function getRateOfChangeAttribute()
    {
        return (float) ($this->attributes['rate_of_change'] / 100);
    }
}

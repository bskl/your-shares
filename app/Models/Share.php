<?php

namespace App\Models;

use Money\Money;
use Money\Currency;

class Share extends BaseModel
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
        'portfolio_id', 'symbol_id', 'lot', 'average', 'average_amount', 'total_amount', 'gain'
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'average', 'average_amount', 'total_amount', 'gain'
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'symbol',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'average_formatted', 'average_amount_formatted', 'total_amount_formatted', 'gain_formatted',
    ];

    /**
     * Get the portfolio.
     */
    public function portfolio()
    {
        return $this->belongsTo('App\Models\Portfolio');
    }

    /**
     * Get the symbol.
     */
    public function symbol()
    {
        return $this->belongsTo('App\Models\Symbol');
    }

    public function calculateTotalAmount(Money $lastPrice)
    {
        $this->total_amount = $lastPrice->multiply($this->lot);
    }

    /**
     * Get the average attribute with the given formatted money.
     */
    public function getAverageFormattedAttribute()
    {
        return $this->getFormattedAmount($this->average);
    }

    /**
     * Get the average price attribute with money object.
     */
    public function getTotalAverageAttribute()
    {
        return $this->average->multiply($this->lot);
    }

    /**
     * Get the average attribute with the given formatted money.
     */
    public function getTotalAverageFormattedAttribute()
    {
        return $this->getFormattedAmount($this->total_average);
    }

    /**
     * Get the amount price attribute with money object.
     */
    public function getAmountAttribute()
    {
        return $this->symbol->last_price->multiply($this->share);
    }

    /**
     * Get the amount with the given formatted money.
     */
    public function getAmountFormattedAttribute()
    {
        return $this->getFormattedAmount($this->amount);
    }

    /**
     * Get the gain price attribute with money object.
     */
    public function getGainAttribute()
    {
        return $this->amount->subtract($this->total_average);
    }
 
     /**
      * Get the gain with the given formatted money.
      */
    public function getGainFormattedAttribute()
    {
        return $this->getFormattedAmount($this->gain);
    }
}

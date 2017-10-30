<?php

namespace App\Models;

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
        'portfolio_id', 'symbol_id', 'lot', 'average', 'average_amount', 'total_amount', 'gain',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'average', 'average_amount', 'total_amount', 'gain',
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

    /**
     * Calculate the total amount price attribute with money object.
     */
    public function calculateTotalAmount(Money $lastPrice)
    {
        $this->total_amount = $lastPrice->multiply($this->lot);
    }

    /**
     * Calculate the average price attribute with money object.
     */
    public function calculateAverageAmount()
    {
        $this->average_amount = $this->average->multiply($this->lot);
    }

    /**
     * Calculate the gain attribute with money object.
     */
    public function calculateGain()
    {
        $this->gain = $this->total_amount->subtract($this->average_amount);
    }

    /**
     * Get the last_price attribute with decimal formatted.
     */
    public function getAverageAttribute()
    {
        return $this->convertMoneyToDecimal(
                $this->getMoneyAttribute('average')
            );
    }

    public function getAverageAmountAttribute()
    {
        return $this->convertMoneyToDecimal(
                $this->getMoneyAttribute('average_amount')
            );
    }

    public function getTotalAmountAttribute()
    {
        return $this->convertMoneyToDecimal(
                $this->getMoneyAttribute('total_amount')
            );
    }

    public function getGainAttribute()
    {
        return $this->convertMoneyToDecimal(
                $this->getMoneyAttribute('gain')
            );
    }
}

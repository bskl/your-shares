<?php

namespace App\Models;

use Money\Money;
use App\Enums\TransactionTypes;

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
        'user_id', 'portfolio_id', 'symbol_id', 'lot', 'total_bonus_issue_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'average', 'average_amount', 'amount', 'gain', 'total_sale_amount', 'total_purchase_amount', 'total_paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain', 'total_gain',
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
     * Get the user that owns the share.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

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
     * Get the share's transactions.
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction')->oldest('date_at');
    }

    /**
     * Get the share's transactions by type.
     */
    public function getTransactionsByType(array $type)
    {
        return $this->transactions->whereIn('type', $type);
    }

    /**
     * Get the share's buying transactions by remaining.
     */
    public function getBuyingTransactionsByNotSold()
    {
        return $this->getTransactionsByType([TransactionTypes::BUYING, TransactionTypes::BONUSISSUE])->where('remaining', '!=', 0);
    }

    /**
     * Calculate the total amount price attribute with money object.
     */
    public function calculateAmount(Money $lastPrice)
    {
        $this->amount = $lastPrice->multiply($this->lot);
    }

    /**
     * Calculate the gain attribute with money object.
     */
    public function calculateGain()
    {
        $this->gain = $this->amount->subtract($this->average_amount);
    }
}

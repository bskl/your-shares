<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use Money\Money;

class Share extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'average_with_dividend', 'average_amount_with_dividend', 'gain_with_dividend',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'portfolio_id', 'symbol_id', 'lot', 'total_bonus_share', 'total_rights_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'average', 'average_with_dividend', 'average_amount', 'average_amount_with_dividend', 'amount', 'gain', 'gain_with_dividend',
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain',
        'total_gain', 'instant_gain',
    ];

    /**
     * The attributes that are format decimal.
     *
     * @var array
     */
    protected $decimal = [
        'lot', 'total_bonus_share', 'total_rights_share',
    ];

    /**
     * The attributes that should be encrypted/decrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'lot', 'average', 'average_with_dividend', 'average_amount', 'average_amount_with_dividend', 'amount', 'gain', 'gain_with_dividend',
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_dividend_gain', 'total_bonus_share',
        'total_rights_share', 'total_gain',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'symbol_id', 'created_at', 'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'instant_gain', 'gain_trend', 'gain_with_dividend_trend',
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
     *
     * @param mixed $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactionsOfType(array $type)
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * Get the share's buying and bonus transactions by remaining.
     */
    public function getTransactionsByTypeAndNotSold()
    {
        return $this->transactionsOfType([TransactionType::Buying, TransactionType::Bonus, TransactionType::Rights])
                    ->where('remaining', '!=', 0)
                    ->get();
    }

    /**
     * Get the share's buying and bonus transactions by remaining.
     */
    public function getTransactionsByTypeAndSold()
    {
        return $this->transactionsOfType([TransactionType::Buying, TransactionType::Bonus, TransactionType::Rights])
                    ->where('lot', '!=', 'remaining')
                    ->get();
    }

    /**
     * Get instant gains to share based on instant prices.
     */
    public function getInstantGainAttribute()
    {
        return $this->gain->add($this->total_gain);
    }

    /**
     * Get instant gain trend.
     */
    public function getGainTrendAttribute()
    {
        return $this->gain->isPositive() ? 1 : ($this->gain->isNegative() ? -1 : 0);
    }

    /**
     * Get instant gain with dividend trend.
     */
    public function getGainWithDividendTrendAttribute()
    {
        return $this->gain_with_dividend->isPositive() ? 1 : ($this->gain_with_dividend->isNegative() ? -1 : 0);
    }

    /**
     * Calculate the total amount price attribute with money object.
     */
    public function setAmount()
    {
        $this->amount = $this->symbol->last_price->multiply($this->lot);
    }

    /**
     * Calculate the gain attribute with money object.
     */
    public function setGain()
    {
        $this->gain = $this->amount->subtract($this->average_amount);
    }

    /**
     * Calculate the gain with dividend attribute with money object.
     */
    public function setGainWithDividend()
    {
        $this->gain_with_dividend = $this->amount->subtract($this->average_amount_with_dividend);
    }
}

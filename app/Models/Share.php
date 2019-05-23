<?php

namespace App\Models;

use App\Enums\TransactionTypes;
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
        'user_id', 'portfolio_id', 'symbol_id', 'lot', 'total_bonus_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'average', 'average_with_dividend', 'average_amount', 'average_amount_with_dividend', 'amount', 'gain', 'gain_with_dividend', 'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain', 'total_gain',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('lot', function (Builder $builder) {
            $builder->orderByRaw('CASE WHEN lot = 0 THEN lot END ASC, symbol_id ASC');
        });
    }

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
     * Get the share's buying and bonus transactions by remaining.
     */
    public function getBuyingTransactionsByNotSold()
    {
        return $this->getTransactionsByType([TransactionTypes::BUYING, TransactionTypes::BONUS])->where('remaining', '!=', 0);
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

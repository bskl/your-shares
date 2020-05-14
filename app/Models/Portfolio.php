<?php

namespace App\Models;

use App\Traits\CanFilterByUser;
use Money\Money;

class Portfolio extends BaseModel
{
    use CanFilterByUser;

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
        'user_id', 'name', 'currency', 'commission', 'order', 'total_bonus_share', 'total_rights_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain',
        'total_gain', 'instant_gain',
    ];

    /**
     * The attributes that are format decimal.
     *
     * @var array
     */
    protected $decimal = [
        'total_bonus_share', 'total_rights_share',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'order', 'created_at', 'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'instant_gain',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'shares',
    ];

    /**
     * The attributes that should be encrypted/decrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_dividend_gain', 'total_bonus_share',
        'total_rights_share', 'total_gain',
    ];

    /**
     * Get the user that owns the portfolio.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The shares that belong to the portfolio.
     */
    public function shares()
    {
        return $this->hasMany('App\Models\Share')
                    ->orderBy('symbol_id', 'asc');
    }

    /**
     * Get all of the transactions for the portfolio.
     */
    public function transactions()
    {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\Share');
    }

    /**
     * Get the portfolio's transactions by type.
     *
     * @param array $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactionsOfType(array $type)
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * Get the commission attribute with remove zeros from end of number ie. 0,18800 becomes 0,188.
     *
     * @return float
     */
    public function getCommissionAttribute(): float
    {
        if ($this->attributes['commission']) {
            return floatval($this->attributes['commission']);
        }
    }

    /**
     * Get instant gains to all shares based on instant prices.
     *
     * @return \Money\Money $instant_gain
     */
    public function getInstantGainAttribute(): Money
    {
        $sharesGain = $this->getMoneyAttribute('0');

        $this->shares->each(function ($share) use (&$sharesGain) {
            $sharesGain = $sharesGain->add($share->gain);
        });

        return $sharesGain->add($this->total_gain);
    }

    /**
     * Handle buying transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleBuyingCalculations(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($transaction->commission_price);
        $this->update();
    }

    /**
     * Handle deleted buying transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedBuyingCalculations(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($transaction->commission_price);
        $this->update();
    }

    /**
     * Handle sale transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     * @param \Money\Money            $gain
     * @param \Money\Money            $amount
     *
     * @return void
     */
    public function handleSaleCalculations(Transaction $transaction, Money $gain, Money $amount): void
    {
        $this->paid_amount = $this->paid_amount->subtract($amount);
        $this->gain_loss = $this->gain_loss->add($gain);
        $this->total_sale_amount = $this->total_sale_amount->add($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($gain)->subtract($transaction->commission_price);
        $this->update();
    }

    /**
     * Handle deleted sale transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     * @param \Money\Money            $gain
     * @param \Money\Money            $amount
     *
     * @return void
     */
    public function handleDeletedSaleCalculations(Transaction $transaction, Money $gain, Money $amount): void
    {
        $this->paid_amount = $this->paid_amount->add($amount);
        $this->gain_loss = $this->gain_loss->subtract($gain);
        $this->total_sale_amount = $this->total_sale_amount->subtract($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($gain)->add($transaction->commission_price);
        $this->update();
    }

    /**
     * Handle dividend transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDividendCalculations(Transaction $transaction): void
    {
        $this->total_dividend_gain = $this->total_dividend_gain->add($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->add($transaction->dividend_gain);
        $this->update();
    }

    /**
     * Handle deleted dividend transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedDividendCalculations(Transaction $transaction): void
    {
        $this->total_dividend_gain = $this->total_dividend_gain->subtract($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->subtract($transaction->dividend_gain);
        $this->update();
    }

    /**
     * Handle bonus transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleBonusCalculations(Transaction $transaction): void
    {
        $this->total_bonus_share += $transaction->lot;
        $this->update();
    }

    /**
     * Handle deleted bonus transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedBonusCalculations(Transaction $transaction): void
    {
        $this->total_bonus_share -= $transaction->lot;
        $this->update();
    }

    /**
     * Handle rights transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleRightsCalculations(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_rights_share += $transaction->lot;
        $this->update();
    }

    /**
     * Handle deleted rights transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedRightsCalculations(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_rights_share -= $transaction->lot;
        $this->update();
    }
}

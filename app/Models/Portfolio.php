<?php

namespace App\Models;

use App\Models\Symbol;
use App\Traits\CanFilterByUser;
use App\Traits\MoneyManager;
use Money\Money;

class Portfolio extends BaseModel
{
    use CanFilterByUser, MoneyManager;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'commission' => 'float',
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
        return $this->hasMany('App\Models\Share');
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
     * Get instant gains to all shares based on instant prices.
     *
     * @return string
     */
    public function getInstantGainAttribute(): string
    {
        $sharesGain = $this->createMoney();

        $this->shares->each(function ($share) use (&$sharesGain) {
            $sharesGain = $sharesGain->add($share->gain);
        });

        return $this->formatByIntl($sharesGain->add($this->total_gain));
    }

    /**
     * Handle buying transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleCalculationsOfBuying(Transaction $transaction): void
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
    public function handleCalculationsOfDeletedBuying(Transaction $transaction): void
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
    public function handleCalculationsOfSale(Transaction $transaction, Money $gain, Money $amount): void
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
    public function handleCalculationsOfDeletedSale(Transaction $transaction, Money $gain, Money $amount): void
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
    public function handleCalculationsOfDividend(Transaction $transaction): void
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
    public function handleCalculationsOfDeletedDividend(Transaction $transaction): void
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
    public function handleCalculationsOfBonus(Transaction $transaction): void
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
    public function handleCalculationsOfDeletedBonus(Transaction $transaction): void
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
    public function handleCalculationsOfRights(Transaction $transaction): void
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
    public function handleCalculationsOfDeletedRights(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_rights_share -= $transaction->lot;
        $this->update();
    }

    /**
     * Handle merger out transaction calculations.
     *
     * @param \App\Models\Transaction $transaction
     *
     * @return \App\Models\Share
     */
    public function handleCalculationsOfMergerOut(Transaction $transaction): Share
    {
        $this->paid_amount = $this->paid_amount->subtract($transaction->share->amount);
        $this->update();

        return $this->shares()->firstOrCreate([
            'symbol_id' => Symbol::whereCode($transaction->symbol_code)->first()->id,
        ]);
    }
}

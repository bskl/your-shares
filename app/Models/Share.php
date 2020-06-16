<?php

namespace App\Models;

use App\Enums\TransactionType;
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
    public function getInstantGainAttribute(): Money
    {
        return $this->gain->add($this->total_gain);
    }

    /**
     * Get instant gain trend.
     *
     * @return int
     */
    public function getGainTrendAttribute(): int
    {
        return $this->gain->isPositive() ? 1 : ($this->gain->isNegative() ? -1 : 0);
    }

    /**
     * Get instant gain with dividend trend.
     *
     * @return int
     */
    public function getGainWithDividendTrendAttribute(): int
    {
        return $this->gain_with_dividend->isPositive() ? 1 : ($this->gain_with_dividend->isNegative() ? -1 : 0);
    }

    /**
     * Calculate the gain with dividend attribute with money object.
     *
     * @return void
     */
    public function setGainWithDividend(): void
    {
        $this->gain_with_dividend = $this->amount->subtract($this->average_amount_with_dividend);
    }

    /**
     * Calculate common attributes with money object.
     *
     * @return void
     */
    public function handleCommonCalculations(): void
    {
        $this->amount = $this->symbol->last_price->multiply($this->lot);
        $this->gain = $this->amount->subtract($this->average_amount);
        $this->setGainWithDividend();
        $this->update();
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
        $this->lot += $transaction->lot;
        $this->average_amount = $this->average_amount->add($transaction->amount);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($transaction->amount);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($transaction->commission_price);
        $this->handleCommonCalculations();
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
        $this->lot -= $transaction->lot;
        $this->average_amount = $this->average_amount->subtract($transaction->amount);
        $this->average = ($this->lot == 0) ? '0' : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->amount);
        $this->average_with_dividend = ($this->lot == 0) ? '0' : $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($transaction->commission_price);
        $this->handleCommonCalculations();
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
        $this->lot -= $transaction->lot;
        $this->average_amount = $this->average_amount->subtract($amount);
        $this->average = ($this->lot == 0) ? '0' : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($amount);
        $this->average_with_dividend = ($this->lot == 0) ? '0' : $this->average_amount_with_dividend->divide($this->lot);
        $this->total_sale_amount = $this->total_sale_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($amount);
        $this->gain_loss = $this->gain_loss->add($gain);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($gain)->subtract($transaction->commission_price);
        $this->handleCommonCalculations();
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
        $this->lot += $transaction->lot;
        $this->average_amount = $this->average_amount->add($amount);
        $this->average = ($this->lot == 0) ? '0' : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($amount);
        $this->average_with_dividend = ($this->lot == 0) ? '0' : $this->average_amount_with_dividend->divide($this->lot);
        $this->total_sale_amount = $this->total_sale_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($amount);
        $this->gain_loss = $this->gain_loss->subtract($gain);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($gain)->add($transaction->commission_price);
        $this->handleCommonCalculations();
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
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->dividend_gain);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_dividend_gain = $this->total_dividend_gain->add($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->add($transaction->dividend_gain);
        $this->setGainWithDividend();
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
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($transaction->dividend_gain);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_dividend_gain = $this->total_dividend_gain->subtract($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->subtract($transaction->dividend_gain);
        $this->setGainWithDividend();
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
        $this->lot += $transaction->lot;
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_bonus_share += $transaction->lot;
        $this->handleCommonCalculations();
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
        $this->lot -= $transaction->lot;
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_bonus_share -= $transaction->lot;
        $this->handleCommonCalculations();
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
        $this->lot += $transaction->lot;
        $this->average_amount = $this->average_amount->add($transaction->amount);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($transaction->amount);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_rights_share += $transaction->lot;
        $this->handleCommonCalculations();
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
        $this->lot -= $transaction->lot;
        $this->average_amount = $this->average_amount->subtract($transaction->amount);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->amount);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_rights_share -= $transaction->lot;
        $this->handleCommonCalculations();
    }
}

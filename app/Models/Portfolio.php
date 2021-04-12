<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\Money as MoneyCast;
use App\Traits\MoneyManager;
use Illuminate\Support\Facades\Auth;
use Money\Money;

class Portfolio extends BaseModel
{
    use MoneyManager;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain',
        'total_bonus_share', 'total_rights_share', 'total_gain', 'instant_gain',
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
        'filtered' => 'boolean',
        'total_sale_amount' => MoneyCast::class,
        'total_purchase_amount' => MoneyCast::class,
        'paid_amount' => MoneyCast::class,
        'gain_loss' => MoneyCast::class,
        'total_commission_amount' => MoneyCast::class,
        'total_dividend_gain' => MoneyCast::class,
        'total_bonus_share' => Decimal::class,
        'total_rights_share' => Decimal::class,
        'total_gain' => MoneyCast::class,
        'instant_gain' => MoneyCast::class,
    ];

    /**
     * Scope a query to only include current authenticated user portfolios.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCurrentUser($query)
    {
        return $query->whereUserId(Auth::id());
    }

    /**
     * Get the user that owns the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The shares that belong to the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    /**
     * Get the portfolio's shares by lot.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getSharesByLot()
    {
        return $this->shares()->where('lot', '>', 0)->get();
    }

    /**
     * Get all of the transactions for the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function transactions()
    {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\Share');
    }

    /**
     * Get the portfolio's transactions by type.
     *
     * @param  array  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactionsOfType(array $type)
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * Calculate common attributes with money object.
     *
     * @return void
     */
    public function handleCommonCalculations(): void
    {
        $sharesGain = $this->createMoney();

        foreach ($this->getSharesByLot() as $share) {
            $sharesGain = $sharesGain->add($share->gain);
        }

        $this->instant_gain = $sharesGain->add($this->total_gain);
        $this->update();
    }

    /**
     * Handle buying transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfBuying(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($transaction->commission_price);
        $this->handleCommonCalculations();
    }

    /**
     * Handle deleted buying transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedBuying(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($transaction->commission_price);
        $this->handleCommonCalculations();
    }

    /**
     * Handle sale transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @param  \Money\Money  $gain
     * @param  \Money\Money  $amount
     * @return void
     */
    public function handleCalculationsOfSale(Transaction $transaction, Money $gain, Money $amount): void
    {
        $this->paid_amount = $this->paid_amount->subtract($amount);
        $this->gain_loss = $this->gain_loss->add($gain);
        $this->total_sale_amount = $this->total_sale_amount->add($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->add($transaction->commission_price);
        $this->total_gain = $this->total_gain->add($gain)->subtract($transaction->commission_price);
        $this->handleCommonCalculations();
    }

    /**
     * Handle deleted sale transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @param  \Money\Money  $gain
     * @param  \Money\Money  $amount
     * @return void
     */
    public function handleCalculationsOfDeletedSale(Transaction $transaction, Money $gain, Money $amount): void
    {
        $this->paid_amount = $this->paid_amount->add($amount);
        $this->gain_loss = $this->gain_loss->subtract($gain);
        $this->total_sale_amount = $this->total_sale_amount->subtract($transaction->amount);
        $this->total_commission_amount = $this->total_commission_amount->subtract($transaction->commission_price);
        $this->total_gain = $this->total_gain->subtract($gain)->add($transaction->commission_price);
        $this->handleCommonCalculations();
    }

    /**
     * Handle dividend transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDividend(Transaction $transaction): void
    {
        $this->total_dividend_gain = $this->total_dividend_gain->add($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->add($transaction->dividend_gain);
        $this->handleCommonCalculations();
    }

    /**
     * Handle deleted dividend transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedDividend(Transaction $transaction): void
    {
        $this->total_dividend_gain = $this->total_dividend_gain->subtract($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->subtract($transaction->dividend_gain);
        $this->handleCommonCalculations();
    }

    /**
     * Handle bonus transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfBonus(Transaction $transaction): void
    {
        $this->total_bonus_share += $transaction->lot;
        $this->handleCommonCalculations();
    }

    /**
     * Handle deleted bonus transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedBonus(Transaction $transaction): void
    {
        $this->total_bonus_share -= $transaction->lot;
        $this->handleCommonCalculations();
    }

    /**
     * Handle rights transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfRights(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->total_rights_share += $transaction->lot;
        $this->handleCommonCalculations();
    }

    /**
     * Handle deleted rights transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedRights(Transaction $transaction): void
    {
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_rights_share -= $transaction->lot;
        $this->handleCommonCalculations();
    }

    /**
     * Handle merger out transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \App\Models\Share
     */
    public function handleCalculationsOfMergerOut(Transaction $transaction): Share
    {
        $this->paid_amount = $this->paid_amount->subtract($transaction->share->amount);
        $this->handleCommonCalculations();

        return $this->shares()->firstOrCreate([
            'symbol_id' => Symbol::whereCode($transaction->symbol_code)->first()->id,
        ]);
    }

    /**
     * Handle merger in transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfMergerIn(Transaction $transaction): void
    {
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->handleCommonCalculations();
    }
}

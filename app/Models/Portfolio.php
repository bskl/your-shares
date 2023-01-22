<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\Money as MoneyCast;
use App\Traits\MoneyManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Money\Money;

/**
 * @property \Money\Money $total_sale_amount
 * @property \Money\Money $total_purchase_amount
 * @property \Money\Money $paid_amount
 * @property \Money\Money $gain_loss
 * @property \Money\Money $total_commission_amount
 * @property \Money\Money $total_dividend_gain
 * @property \Money\Money $total_gain
 * @property \Money\Money $instant_gain
 */
class Portfolio extends BaseModel
{
    use MoneyManager;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain',
        'total_bonus_share', 'total_rights_share', 'total_gain', 'instant_gain',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'user_id', 'order', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'sum_amount', 'sum_average_amount', 'sum_gain', 'total_gain_percent',
    ];

    /**
     * {@inheritdoc}
     */
    protected $with = [
        'shares',
    ];

    /**
     * {@inheritdoc}
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
     * @param  \Illuminate\Database\Eloquent\Builder<\App\Models\Portfolio>  $query
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Portfolio>
     */
    public function scopeByCurrentUser(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    /**
     * Get the user that owns the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,\App\Models\Portfolio>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The shares that belong to the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Share>
     */
    public function shares(): HasMany
    {
        return $this->hasMany('App\Models\Share');
    }

    /**
     * Get the portfolio's shares by lot.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Share>
     */
    public function getSharesByLot(): Collection
    {
        return $this->shares()->where('lot', '>', 0)->get();
    }

    /**
     * Get all of the transactions for the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<\App\Models\Transaction>
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\Share');
    }

    /**
     * Get the portfolio's transactions by type.
     *
     * @param  array<int, \App\Enums\TransactionType>  $type
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<\App\Models\Transaction>
     */
    public function transactionsOfType(array $type): HasManyThrough
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * To sum amount attribute of shares with money object.
     *
     * @return string
     */
    public function getSumAmountAttribute(): string
    {
        return $this->formatByIntl($this->sumShareAttribute('amount'));
    }

    /**
     * To sum average amount attribute of shares with money object.
     *
     * @return string
     */
    public function getSumAverageAmountAttribute(): string
    {
        return $this->formatByIntl($this->sumShareAttribute('average_amount'));
    }

    /**
     * To sum gain attribute of shares with money object.
     *
     * @return string
     */
    public function getSumGainAttribute(): string
    {
        return $this->formatByIntl($this->sumShareAttribute('gain'));
    }

    /**
     * Calculate percent of the total gain.
     *
     * @return float
     */
    public function getTotalGainPercentAttribute(): float
    {
        $money = $this->sumShareAttribute('average_amount');

        return $money->isZero()
            ? 0
            : $this->sumShareAttribute('gain')->ratioOf($money);
    }

    /**
     * To sum given attribute of shares with money object.
     *
     * @param  string  $attribute
     * @return \Money\Money
     */
    public function sumShareAttribute(string $attribute): Money
    {
        $money = $this->createMoney();

        foreach ($this->getSharesByLot() as $share) {
            $money = $money->add($share->$attribute);
        }

        return $money;
    }

    /**
     * Calculate common attributes with money object.
     *
     * @return void
     */
    public function handleCommonCalculations(): void
    {
        $this->instant_gain = $this->sumShareAttribute('gain')->add($this->total_gain);

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
    public function handleCalculationsOfMergerIn(Transaction $transaction): void
    {
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->handleCommonCalculations();
    }
}

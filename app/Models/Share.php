<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\Money as MoneyCast;
use App\Enums\TransactionType;
use App\Support\MoneyManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Money\Money;

/**
 * @psalm-property int|numeric-string $lot
 *
 * @property \Money\Money $average
 * @property \Money\Money $average_with_dividend
 * @property \Money\Money $average_amount
 * @property \Money\Money $average_amount_with_dividend
 * @property \Money\Money $amount
 * @property \Money\Money $gain
 * @property \Money\Money $gain_with_dividend
 * @property \Money\Money $total_sale_amount
 * @property \Money\Money $total_purchase_amount
 * @property \Money\Money $paid_amount
 * @property \Money\Money $gain_loss
 * @property \Money\Money $total_commission_amount
 * @property \Money\Money $total_dividend_gain
 * @property \Money\Money $total_gain
 * @property \Money\Money $instant_gain
 */
class Share extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'user_id', 'lot', 'average', 'average_with_dividend', 'average_amount', 'average_amount_with_dividend', 'amount', 'gain',
        'gain_with_dividend', 'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount',
        'total_dividend_gain', 'total_bonus_share', 'total_rights_share', 'total_gain', 'instant_gain',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'user_id', 'symbol_id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'gain_percent', 'gain_trend', 'gain_with_dividend_trend',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'lot' => 'decimal:3',
        'average' => MoneyCast::class,
        'average_with_dividend' => MoneyCast::class,
        'average_amount' => MoneyCast::class,
        'average_amount_with_dividend' => MoneyCast::class,
        'amount' => MoneyCast::class,
        'gain' => MoneyCast::class,
        'gain_with_dividend' => MoneyCast::class,
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
     * {@inheritdoc}
     */
    protected $with = [
        'symbol',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('orderByLot', function (Builder $builder) {
            $builder->orderByRaw('CASE WHEN lot < 1 THEN lot END ASC, symbol_id ASC');
        });
    }

    /**
     * Get the user that owns the share.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,\App\Models\Share>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Portfolio,\App\Models\Share>
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Portfolio::class);
    }

    /**
     * Get the symbol.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Symbol,\App\Models\Share>
     */
    public function symbol(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Symbol::class);
    }

    /**
     * Get the share's transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Transaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction::class)->oldest('date_at');
    }

    /**
     * Get the share's transactions by type.
     *
     * @param  array<int, \App\Enums\TransactionType>  $type
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Transaction>
     */
    public function transactionsOfType(array $type): HasMany
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * Get the share's sold transactions.
     *
     * @param  string  $operator
     * @return \Illuminate\Database\Eloquent\Collection<int,\App\Models\Transaction>
     */
    public function getSoldTransactions(string $operator = '='): Collection
    {
        return $this->transactionsOfType([TransactionType::Buying, TransactionType::Bonus, TransactionType::Rights, TransactionType::MergerIn, TransactionType::PublicOffering])
                    ->where('remaining', $operator, 0)
                    ->get();
    }

    /**
     * Get the share's not sold transactions.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int,\App\Models\Transaction>
     */
    public function getNotSoldTransactions(): Collection
    {
        return $this->getSoldTransactions('!=');
    }

    /**
     * Calculate percent of the gain.
     *
     * @return string
     */
    public function getGainPercentAttribute(): string
    {
        if ($this->average_amount->isZero()) {
            return '0';
        }

        return $this->gain->ratioOf($this->average_amount);
    }

    /**
     * Get instant gain trend.
     *
     * @return int
     */
    public function getGainTrendAttribute(): int
    {
        return MoneyManager::getTrend($this->gain);
    }

    /**
     * Get instant gain with dividend trend.
     *
     * @return int
     */
    public function getGainWithDividendTrendAttribute(): int
    {
        return MoneyManager::getTrend($this->gain_with_dividend);
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
        $this->gain_with_dividend = $this->amount->subtract($this->average_amount_with_dividend);
        $this->instant_gain = $this->gain->add($this->total_gain);
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
        $this->lot = bcadd($this->lot, $transaction->lot, 3);
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
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedBuying(Transaction $transaction): void
    {
        $this->lot = bcsub($this->lot, $transaction->lot, 3);
        $this->average_amount = $this->average_amount->subtract($transaction->amount);
        $this->average = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->amount);
        $this->average_with_dividend = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount_with_dividend->divide($this->lot);
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
        $this->lot = bcsub($this->lot, $transaction->lot, 3);
        $this->average_amount = $this->average_amount->subtract($amount);
        $this->average = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($amount);
        $this->average_with_dividend = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount_with_dividend->divide($this->lot);
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
     * @param  \App\Models\Transaction  $transaction
     * @param  \Money\Money  $gain
     * @param  \Money\Money  $amount
     * @return void
     */
    public function handleCalculationsOfDeletedSale(Transaction $transaction, Money $gain, Money $amount): void
    {
        $this->lot = bcadd($this->lot, $transaction->lot, 3);
        $this->average_amount = $this->average_amount->add($amount);
        $this->average = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($amount);
        $this->average_with_dividend = ($this->lot == '0') ? MoneyManager::createMoney() : $this->average_amount_with_dividend->divide($this->lot);
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
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDividend(Transaction $transaction): void
    {
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->dividend_gain);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_dividend_gain = $this->total_dividend_gain->add($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->add($transaction->dividend_gain);
        $this->gain_with_dividend = $this->amount->subtract($this->average_amount_with_dividend);
        $this->update();
    }

    /**
     * Handle deleted dividend transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedDividend(Transaction $transaction): void
    {
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($transaction->dividend_gain);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_dividend_gain = $this->total_dividend_gain->subtract($transaction->dividend_gain);
        $this->total_gain = $this->total_gain->subtract($transaction->dividend_gain);
        $this->gain_with_dividend = $this->amount->subtract($this->average_amount_with_dividend);
        $this->update();
    }

    /**
     * Handle bonus transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfBonus(Transaction $transaction): void
    {
        $this->lot = bcadd($this->lot, $transaction->lot, 3);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
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
        $this->lot = bcsub($this->lot, $transaction->lot, 3);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
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
        $this->lot = bcadd($this->lot, $transaction->lot, 3);
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
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfDeletedRights(Transaction $transaction): void
    {
        $this->lot = bcsub($this->lot, $transaction->lot, 3);
        $this->average_amount = $this->average_amount->subtract($transaction->amount);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($transaction->amount);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->subtract($transaction->amount);
        $this->paid_amount = $this->paid_amount->subtract($transaction->amount);
        $this->total_rights_share -= $transaction->lot;
        $this->handleCommonCalculations();
    }

    /**
     * Handle merger out transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfMergerOut(Transaction $transaction): void
    {
        $this->lot = bcsub($this->lot, $transaction->lot, 3);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->subtract($this->average_amount);
        $this->paid_amount = $this->paid_amount->subtract($this->average_amount);
        $this->average_amount = $this->average = $this->average_with_dividend = MoneyManager::createMoney();
        $this->handleCommonCalculations();
    }

    /**
     * Handle merger in transaction calculations.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function handleCalculationsOfMergerIn(Transaction $transaction): void
    {
        $this->lot = bcadd($this->lot, $transaction->lot, 3);
        $this->average_amount = $this->average_amount->add($transaction->amount);
        $this->average = $this->average_amount->divide($this->lot);
        $this->average_amount_with_dividend = $this->average_amount_with_dividend->add($transaction->amount);
        $this->average_with_dividend = $this->average_amount_with_dividend->divide($this->lot);
        $this->total_purchase_amount = $this->total_purchase_amount->add($transaction->amount);
        $this->paid_amount = $this->paid_amount->add($transaction->amount);
        $this->handleCommonCalculations();
    }
}

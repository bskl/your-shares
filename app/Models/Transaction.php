<?php

namespace App\Models;

use App\Casts\Money as MoneyCast;
use App\Casts\Percent;
use App\Enums\TransactionType;
use App\Support\MoneyManager;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

/**
 * @property \Money\Money $price
 * @property \Money\Money $amount
 * @property \Money\Money $commission_price
 * @property \Money\Money $sale_average
 * @property \Money\Money $sale_average_amount
 * @property \Money\Money $sale_gain
 * @property \Money\Money $dividend
 * @property \Money\Money $dividend_gain
 * @property int $year
 */
class Transaction extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'user_id', 'remaining', 'amount', 'commission_price', 'sale_average', 'sale_average_amount', 'sale_gain', 'dividend',
        'bonus', 'rights',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'date_at', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'user_id', 'share_id', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'sale_gain_trend',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'type' => TransactionType::class,
        'lot' => 'decimal:3',
        'price' => MoneyCast::class,
        'amount' => MoneyCast::class,
        'commission' => 'decimal:3',
        'commission_price' => MoneyCast::class,
        'sale_average' => MoneyCast::class,
        'sale_average_amount' => MoneyCast::class,
        'sale_gain' => MoneyCast::class,
        'dividend' => MoneyCast::class,
        'dividend_gain' => MoneyCast::class,
        'bonus' => Percent::class,
        'rights' => Percent::class,
        'preference' => 'decimal:3',
        'exchange_ratio' => 'decimal:15',
    ];

    /**
     * Get the transaction share.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Share,\App\Models\Transaction>
     */
    public function share(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Share::class);
    }

    /**
     * Get sale gain trend.
     *
     * @return int
     */
    public function getSaleGainTrendAttribute(): int
    {
        return MoneyManager::getTrend($this->sale_gain);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d.m.Y');
    }

    /**
     * Handle buying transaction calculations.
     *
     * @return void
     */
    public function handleCalculationsOfBuying(): void
    {
        $this->remaining = (int) $this->lot;
        $this->amount = $this->price->multiply($this->lot);
        $this->commission_price = $this->amount->multiply($this->commission)
                                               ->divide(100);
        $this->update();
    }

    /**
     * Handle buying transaction calculations.
     *
     * @param  \Money\Money  $gain
     * @return void
     */
    public function handleCalculationsOfSale(Money $gain): void
    {
        $this->sale_gain = $this->sale_gain->add($gain);
        $this->amount = $this->price->multiply($this->lot);
        $this->commission_price = $this->amount->multiply($this->commission)
                                               ->divide(100);
        $this->update();
    }

    /**
     * Handle bonus transaction calculations.
     *
     * @return void
     */
    public function handleCalculationsOfBonus(): void
    {
        $this->remaining = (int) $this->lot;
        $this->price = MoneyManager::createMoney();
        $this->bonus = ($this->lot * 100) / $this->preference;
        $this->update();
    }

    /**
     * Handle rights transaction calculations.
     *
     * @return void
     */
    public function handleCalculationsOfRights(): void
    {
        $this->remaining = (int) $this->lot;
        $this->price = MoneyManager::createMoney('100');
        $this->amount = $this->price->multiply($this->lot);
        $this->rights = ($this->lot * 100) / $this->preference;
        $this->update();
    }

    /**
     * Handle merger in transaction calculations.
     *
     * @return void
     */
    public function handleCalculationsOfMergerIn(): void
    {
        $this->remaining = (int) $this->lot;
        $this->amount = $this->price->multiply($this->lot);
        $this->update();
    }
}

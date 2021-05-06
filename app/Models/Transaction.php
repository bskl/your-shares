<?php

namespace App\Models;

use App\Casts\Money as MoneyCast;
use App\Casts\Percent;
use App\Enums\TransactionType;
use App\Traits\MoneyMAnager;
use DateTimeInterface;
use Money\Money;

class Transaction extends BaseModel
{
    use MoneyManager;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'user_id', 'remaining', 'amount', 'commission_price', 'sale_average', 'sale_average_amount', 'sale_gain', 'dividend',
        'bonus', 'rights',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_at', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'share_id', 'created_at', 'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'sale_gain_trend',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function share()
    {
        return $this->belongsTo('App\Models\Share');
    }

    /**
     * Set the symbol code attribute.
     *
     * @param  int  $value
     * @return void
     */
    public function setSymbolCodeAttribute($value): void
    {
        if ($this->type->in([TransactionType::MergerOut, TransactionType::MergerIn])) {
            $this->attributes['symbol_code'] = Symbol::findOrFail($value)->code;
        }
    }

    /**
     * Get sale gain trend.
     *
     * @return int
     */
    public function getSaleGainTrendAttribute(): int
    {
        return $this->getTrend($this->sale_gain);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
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
        $this->remaining = $this->lot;
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
        $this->remaining = $this->lot;
        $this->price = $this->createMoney('0');
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
        $this->remaining = $this->lot;
        $this->price = $this->createMoney('100');
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
        $this->remaining = $this->lot;
        $this->amount = $this->price->multiply($this->lot);
        $this->update();
    }
}

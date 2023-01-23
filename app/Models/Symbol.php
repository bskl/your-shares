<?php

namespace App\Models;

use App\Casts\Money;
use App\Casts\Percent;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \Money\Money $last_price
 */
class Symbol extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'session_time', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'name', 'title', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'rate_of_change' => Percent::class,
        'last_price' => Money::class,
    ];

    /**
     * The shares that belong to the symbol.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Share>
     */
    public function shares(): HasMany
    {
        return $this->hasMany(\App\Models\Share::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d.m.Y H:i:s');
    }
}

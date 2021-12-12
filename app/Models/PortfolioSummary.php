<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\Money;

class PortfolioSummary extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'portfolio_summary';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'portfolio_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'portfolio_id', 'type', 'month', 'year', 'amount', 'dividend_gain', 'lot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'lot' => Decimal::class,
        'amount' => Money::class,
        'dividend_gain' => Money::class,
    ];

    /**
     * Get the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio()
    {
        return $this->belongsTo('App\Models\Portfolio');
    }
}

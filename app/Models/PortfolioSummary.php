<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioSummary extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'portfolio_summary';

    /**
     * {@inheritdoc}
     */
    protected $primaryKey = 'portfolio_id';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'portfolio_id', 'type', 'month', 'year', 'amount', 'dividend_gain', 'lot',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'lot' => Decimal::class,
        'amount' => Money::class,
        'dividend_gain' => Money::class,
    ];

    /**
     * Get the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Portfolio,\App\Models\PortfolioSummary>
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo('App\Models\Portfolio');
    }
}

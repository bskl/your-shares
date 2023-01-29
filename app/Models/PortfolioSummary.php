<?php

namespace App\Models;

use App\Casts\Decimal;
use App\Casts\MoneyCast;
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
        'amount' => MoneyCast::class,
        'dividend_gain' => MoneyCast::class,
    ];

    /**
     * Get the portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Portfolio,\App\Models\PortfolioSummary>
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Portfolio::class);
    }
}

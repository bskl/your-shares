<?php

namespace App\Models;

use App\Traits\CanFilterByUser;
use Money\Money;

class Portfolio extends BaseModel
{
    use CanFilterByUser;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'currency', 'order', 'total_bonus_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain', 'total_gain',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'created_at', 'updated_at',
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
     * Get the user that owns the portfolio.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The shares that belong to the portfolio.
     */
    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    /**
     * Calculate the total amount price attribute with money object.
     */
    public function calculateMoneyAttributes()
    {
        $totalCommission = $totalDividend = Money::TRY(0);
        $totalBonus = 0;

        $this->shares->each(function ($share) use (&$totalCommission, &$totalDividend, &$totalBonus) {
            $totalCommission = $totalCommission->add($share->total_commission_amount);
            $totalDividend = $totalDividend->add($share->total_dividend_gain);
            $totalBonus = $totalBonus + $share->total_bonus_share;
        });

        $this->total_commission_amount = $totalCommission;
        $this->total_dividend_gain = $totalDividend;
        $this->total_bonus_share = $totalBonus;
        $this->update();
    }
}

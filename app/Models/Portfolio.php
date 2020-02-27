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
        'user_id', 'name', 'currency', 'commission', 'order', 'total_bonus_share', 'total_rights_share',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_commission_amount', 'total_dividend_gain',
        'total_gain',
    ];

    /**
     * The attributes that are format decimal.
     *
     * @var array
     */
    protected $decimal = [
        'total_bonus_share', 'total_rights_share',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'instant_gain',
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
     * The attributes that should be encrypted/decrypted.
     * 
     * @var array
     */
    protected $encryptable = [
        'total_sale_amount', 'total_purchase_amount', 'paid_amount', 'gain_loss', 'total_dividend_gain', 'total_bonus_share',
        'total_rights_share', 'total_gain',
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
     * Get all of the transactions for the portfolio.
     */
    public function transactions()
    {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\Share');
    }

    /**
     * Get the portfolio's transactions by type.
     *
     * @param mixed $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function transactionsOfType($type)
    {
        return $this->transactions()->whereIn('type', $type);
    }

    /**
     * Get the commission attribute with remove zeros from end of number ie. 0,18800 becomes 0,188.
     */
    public function getCommissionAttribute()
    {
        if ($this->attributes['commission']) {
            return floatval($this->attributes['commission']);
        }
    }

    /**
     * Get instant gains to all shares based on instant prices.
     */
    public function getInstantGainAttribute()
    {
        $sharesGain = Money::TRY(0);

        $this->shares->each(function ($share) use (&$sharesGain) {
            $sharesGain = $sharesGain->add($share->gain);
        });

        return money_formatter($sharesGain->add($this->total_gain));
    }

    /**
     * Calculate the total amount price attribute with money object.
     */
    public function calculateMoneyAttributes()
    {
        $totalCommission = $totalDividend = Money::TRY(0);
        $totalBonus = $totalRights = 0;

        $this->shares->each(function ($share) use (&$totalCommission, &$totalDividend, &$totalBonus, &$totalRights) {
            $totalCommission = $totalCommission->add($share->total_commission_amount);
            $totalDividend = $totalDividend->add($share->total_dividend_gain);
            $totalBonus = $totalBonus + $share->total_bonus_share;
            $totalRights = $totalRights + $share->total_rights_share;
        });

        $this->total_commission_amount = $totalCommission;
        $this->total_dividend_gain = $totalDividend;
        $this->total_bonus_share = $totalBonus;
        $this->total_rights_share = $totalRights;
        $this->update();
    }
}

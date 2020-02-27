<?php

namespace App\Models;

use App\Enums\TransactionType;
use BenSampo\Enum\Traits\CastsEnums;
use Carbon\Carbon;
use Money\Money;

class Transaction extends BaseModel
{
    use CastsEnums;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'user_id', 'price', 'amount', 'commission_price', 'sale_average', 'sale_average_amount', 'sale_gain', 'dividend', 'dividend_gain', 'bonus',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'share_id', 'type', 'date_at', 'lot', 'remaining', 'commission',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'price', 'amount', 'commission_price', 'sale_average', 'sale_average_amount', 'sale_gain', 'dividend', 'dividend_gain',
    ];

    /**
     * The attributes that are format percentages.
     *
     * @var array
     */
    protected $percent = [
        'bonus', 'rights',
    ];

    /**
     * The attributes that are format decimal.
     *
     * @var array
     */
    protected $decimal = [
        'lot',
    ];

    /**
     * The attributes that should be encrypted/decrypted.
     * 
     * @var array
     */
    protected $encryptable = [
        'lot', 'amount', 'sale_average_amount', 'sale_gain', 'dividend_gain', 'bonus', 'rights',
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
        'created_at', 'updated_at',
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
     * The attributes that should be mutated to enum class.
     *
     * @var array
     */
    protected $enumCasts = [
        'type' => TransactionType::class,
    ];

    /**
     * Get the transaction share.
     */
    public function share()
    {
        return $this->belongsTo('App\Models\Share');
    }

    /**
     * Set the date_at attribute with Carbon object.
     */
    public function setDateAtAttribute($value)
    {
        if ($value) {
            $this->attributes['date_at'] = Carbon::createFromFormat('d.m.Y', $value)->toDateTimeString();
        }
    }

    /**
     * Get the date_at attribute with Carbon object.
     */
    public function getDateAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized('%d.%m.%Y');
        }
    }

    /**
     * Get sale gain trend.
     */
    public function getSaleGainTrendAttribute()
    {
        return $this->sale_gain->getAmount() > 0 ? 1 : ($this->sale_gain->getAmount() < 0 ? -1 : 0);
    }

    /**
     * Set the bonus attribute with divided 100.
     */
    public function setBonusAttribute($value)
    {
        if ($value) {
            return (float) $this->attributes['bonus'] = ($value / 100);
        }
    }

    /**
     * Set the rights attribute with divided 100.
     */
    public function setRightsAttribute($value)
    {
        if ($value) {
            return (float) $this->attributes['rights'] = ($value / 100);
        }
    }
}

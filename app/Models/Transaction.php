<?php

namespace App\Models;

use Carbon\Carbon;

class Transaction extends BaseModel
{
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
        'share_id', 'type', 'date', 'lot', 'price', 'amount', 'commission', 'commission_price', 'average', 'sale_gain',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'price', 'amount', 'commission_price', 'average', 'sale_gain',
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
     * Get the portfolio symbol.
     */
    public function share()
    {
        return $this->belongsTo('App\Models\Share');
    }

    /**
     * Set the date attribute.
     */
    public function setDateAttribute($date)
    {
        if ($date) {
            $this->attributes['date'] = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        }
    }
}

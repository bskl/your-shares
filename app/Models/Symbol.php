<?php

namespace App\Models;

use App\Events\SymbolUpdated;

class Symbol extends BaseModel
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
        'code', 'name', 'title', 'trend', 'last_price', 'rate_of_change', 'session_time',
    ];

    /**
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'last_price',
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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => SymbolUpdated::class,
    ];

    /**
     * Get the rate_of_change attribute with divided 100.
     */
    public function getRateOfChangeAttribute()
    {
        return (float) ($this->attributes['rate_of_change'] / 100);
    }

    /**
     * Get the last_price attribute with decimal formatted.
     *
    *public function getLastPriceAttribute()
    *{
    *    return $this->convertMoneyToDecimal(
    *            $this->getMoneyAttribute('last_price')
    *        );
    *}
    */
}

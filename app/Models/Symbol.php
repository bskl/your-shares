<?php

namespace App\Models;

use App\Events\SymbolUpdated;
use Carbon\Carbon;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'session_time', 'created_at', 'updated_at',
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
     * The shares that belong to the symbol.
     */
    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => SymbolUpdated::class,
    ];

    /**
     * Get the updated at attribute with convert to human readable timestamp.
     */
    public function getSessionTimeAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['session_time'])->format('H:i');
    }
}

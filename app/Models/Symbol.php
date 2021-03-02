<?php

namespace App\Models;

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
     * The attributes that are money object.
     *
     * @var array
     */
    protected $money = [
        'last_price',
    ];

    /**
     * The attributes that are format percentages.
     *
     * @var array
     */
    protected $percent = [
        'rate_of_change',
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
        'name', 'title', 'created_at', 'updated_at',
    ];

    /**
     * The shares that belong to the symbol.
     */
    public function shares()
    {
        return $this->hasMany('App\Models\Share');
    }

    /**
     * Get the updated at attribute with convert to human readable timestamp.
     *
     * @return string
     */
    public function getSessionTimeAttribute(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['session_time'])->format('d-m-Y H:i:s');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'user_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'order',
    ];

    /**
     * Get the user that owns the portfolio.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The user symbols that belong to the portfolio.
     */
    public function userSymbols()
    {
        return $this->hasMany('App\Models\UserSymbol', 'symbol');
    }
}

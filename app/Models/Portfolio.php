<?php

namespace App\Models;

use App\Traits\CanFilterByUser;

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
        'user_id', 'name', 'currency', 'order',
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
     * Get the user that owns the portfolio.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The symbols that belong to the portfolio.
     */
    public function symbols()
    {
        return $this->belongsToMany('App\Models\Symbol');
    }
}

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
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['session_time', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'session_time', 'created_at', 'updated_at',
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

}

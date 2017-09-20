<?php

namespace App\Models;

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
        'code', 'name', 'title',
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
     * The portfolios that belong to the symbol.
     */
     public function portfolios()
     {
         return $this->belongsToMany('App\Models\Portfolio');
     }
}

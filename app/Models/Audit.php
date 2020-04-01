<?php

namespace App\Models;

class Audit extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'user_id', 'logon_at', 'ip_address', 'user_agent',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logon_at', 'ip_address', 'user_agent',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'logon_at', 'created_at', 'updated_at',
    ];

    /**
     * Get the user that owns the audit.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
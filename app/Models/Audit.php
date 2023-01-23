<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Audit extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'user_id', 'logon_at', 'ip_address', 'user_agent',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'logon_at', 'created_at', 'updated_at',
    ];

    /**
     * Get the user that owns the audit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,\App\Models\Audit>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d.m.Y');
    }
}

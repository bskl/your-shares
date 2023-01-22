<?php

namespace App\Models;

use App\Enums\UserType;
use App\Notifications\ResetPassword as PasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'confirmed', 'confirmation_code', 'remember_token',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password', 'two_factor_secret', 'two_factor_recovery_codes', 'confirmed', 'confirmation_code', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'role' => UserType::class,
        'confirmed' => UserType::class,
    ];

    /**
     * Get the portfolios for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Portfolio>
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany('App\Models\Portfolio');
    }

    /**
     * Get the audits for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Audit>
     */
    public function audits(): HasMany
    {
        return $this->hasMany('App\Models\Audit');
    }

    /**
     * {@inheritdoc}
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role->is(UserType::Admin);
    }
}

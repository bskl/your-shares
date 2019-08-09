<?php

namespace App\Models;

use App\Enums\UserType;
use App\Notifications\ResetPassword as PasswordResetNotification;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CastsEnums;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'locale', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be mutated to enum class.
     *
     * @var array
     */
    protected $enumCasts = [
        'role' => UserType::class,
    ];

    /**
     * Get the portfolios for the user.
     */
    public function portfolios()
    {
        return $this->hasMany('App\Models\Portfolio');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}

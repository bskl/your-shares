<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Indicate that a (Model) object collection can be filtered by the current authenticated user.
 */
trait CanFilterByUser
{
    public function scopeByCurrentUser($query)
    {
        return $query->whereUserId(Auth::id());
    }
}

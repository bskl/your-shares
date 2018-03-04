<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Set user language for auth user.
     *
     * @string $locale
     */
    public function setLocale(string $locale)
    {
        Auth::user()->locale = $locale;
        Auth::user()->update();
    }
}

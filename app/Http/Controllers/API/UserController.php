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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLocale(string $locale)
    {
        Auth::user()->locale = $locale;
        try {
            Auth::user()->update();

            return $this->respondSuccess([]);
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.user.update_error')]
            );
        }
    }
}

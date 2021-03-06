<?php

namespace App\Http\Controllers\API;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Set user language for auth user.
     *
     * @param  string  $locale
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

    /**
     * Handle a registration request for the application.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyConfirmationCode(StatefulGuard $guard, string $token)
    {
        $user = User::where('confirmation_code', $token)->firstOrFail();

        try {
            $user->confirmed = UserType::Accepted;
            $user->confirmation_code = null;
            $user->save();

            $guard->login($user);

            return $this->respondSuccess([], trans('app.user.verified'));
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.user.verified_error')]
            );
        }
    }
}

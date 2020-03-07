<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Logout Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles logout for authenticating users.
    |
    */

    /**
     * Logout a request to the OAuth server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $tokens = Auth::user()->tokens;

        $tokens->map(function ($token) {
            $token->revoke();
        });

        return $this->respondSuccess([]);
    }
}

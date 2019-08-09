<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * Login a request to the OAuth server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return json $response
     */
    public function logout(Request $request)
    {
        $tokens = Auth::user()->tokens;

        $tokens->map(function ($token) {
            $token->revoke();
        });

        $json = [
            'success' => true,
            'code'    => Response::HTTP_OK,
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}

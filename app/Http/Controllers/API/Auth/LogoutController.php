<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return json  $response
     */
    public function logout(Request $request)
    {
        $tokens = Auth::user()->tokens;
        
        $tokens->map(function($token) {
            $token->revoke();
        });

        $json = [
            'success' => true,
            'code' => 200,
        ];

        return response()->json($json, '200');
    }
}
<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;

class LoginController extends Controller
{
    use LoginUsers;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Login a request to the OAuth server.
     *
     * @param \App\Http\Requests\API\LoginRequest $request
     *
     * @return json $response
     */
    public function login(LoginRequest $request)
    {
        return $this->loginUser($request);
    }
}

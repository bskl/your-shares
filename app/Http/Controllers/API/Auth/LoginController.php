<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\API\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LoginController extends Controller
{
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
     * The http instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Create a new controller instance.
     *
     * @param  \GuzzleHttp\Client  $http
     * @return void
     */
    public function __construct(Client $http)
    {
        $this->http = $http;

        $this->middleware('guest')->except('logout');
    }

    /**
     * Login a request to the OAuth server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json  $response
     */
    public function login(LoginRequest $request)
    {
        return $this->attempLogin($this->credentials($request));
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @return json  $response
     */
    public function attempLogin(array $credentials)
    {
        $url = env('APP_URL') . '/oauth/token';
        
        $response = $this->http->post($url, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSWORD_CLIENT_SECRET'),
                'username' => $credentials['email'],
                'password' => $credentials['password'],
                'scope' => '*',
                ],
            ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }
}

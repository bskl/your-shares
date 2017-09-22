<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use App\Http\Requests\API\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    private $loginController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;

        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        event(new Registered($user));

        return $this->loginController->attempLogin(
            $this->loginController->credentials($request)
        );
    }
}

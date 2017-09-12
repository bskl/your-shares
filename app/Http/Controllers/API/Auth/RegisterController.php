<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use App\Models\Portfolio;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

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

        $this->createStandart($user);

        return $this->loginController->attempLogin(
            $this->loginController->credentials($request)
        );
    }

    /**
     * Create standart data for new user.
     * 
     * @param  App\Models\User  $user
     * @return App\Models\Portfolio
     */
    public function createStandart(User $user)
    {
        Portfolio::create([
            'user_id' => $user['id'],
            'name' => Lang::get('app.portfolio.default'),
            'order' => 1,
        ]);
    }
}

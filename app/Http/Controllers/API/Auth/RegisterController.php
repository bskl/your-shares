<?php

namespace App\Http\Controllers\API\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use App\Notifications\ConfirmationCode as ConfirmationCodeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use LoginUsers;

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
     * @param \App\Http\Requests\API\RegisterRequest $request
     *
     * @return json $response
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'locale'   => config('app.locale'), //$request->getPreferredLanguage(['tr', 'en']),
        ]);

        $user->confirmation_code = hash_hmac('sha256', Str::random(60), config('app.key'));
        $user->save();

        $user->notify(new ConfirmationCodeNotification($user->confirmation_code));

        event(new Registered($user));

        return $this->loginUser($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $token
     *
     * @return JsonResponse
     */
    public function verifyConfirmationCode(Request $request, string $token)
    {
        $user = User::where('confirmation_code', $token)->firstOrFail();
        $user->confirmed = UserType::Accepted;
        $user->confirmation_code = null;

        try {
            $user->save();

            return $this->respondSuccess([], trans('app.user.verified'));
        } catch (\Exception $e) {
            return $this->respondError(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [trans('app.user.verified_error')]
            );
        }
    }
}

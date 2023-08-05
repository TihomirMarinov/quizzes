<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Validators\RestValidator;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Exceptions\CustomValidationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication",
 * )
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function sendLoginResponse(Request $request)
    {
        $token = $request->user()->createToken(config('app.name'))->accessToken;

        $cookie = UserService::getUserCookieDetails($token);
        $user = User::find($request->user()->id);

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ])->cookie(
            $cookie['name'],
            $cookie['value'],
            $cookie['minutes'],
            $cookie['path'],
            $cookie['domain'],
            $cookie['secure'],
            $cookie['httponly'],
            $cookie['samesite']
        );
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     summary="Sign-in",
     *     description="Perform a new authentication with email and password",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User Credentials",
     *         @OA\JsonContent(ref="#/components/schemas/InputLogin"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/UserAuth",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/DefaultBadRequest")),
     * )
     */
    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        $validationError = [
            'code' => RestValidator::ERROR_LOGIN,
            'rule' => 'login',
            'message' => trans('auth.failed'),
        ];

        throw CustomValidationException::withMessages([
            $this->username() => [$validationError],
            'password' => [$validationError],
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use Auth;
use Authy\AuthyApi;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

/**
 * @group Auth Management
 * 
 * APIs for managing authentication
 */
class AuthController extends Controller
{
    /**
     * Me API
     * 
     * This endpoint will return the currently logged-in user.
     * 
     * @authenticated
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function me()
    {
        $user = Auth::user();
        $user->roles = $user->roles()->select('slug', 'name', 'permissions')->get();
        return response()->success($user);
    }
    /**
     * Login API
     * 
     * This endpoint allows you to login users.
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $this->processCredentials($request);
        # attempt to login
        if ($user = Sentinel::stateless($credentials)) {
            // If has phone number

            $isGoogleMultiFactor = (bool) $user->google2fa->count();
            $isTwilioAuthyTwoFactor = (bool) $user->phone_number;
            $token = $user->createToken('MyApp')->accessToken;
            if ($isGoogleMultiFactor)
                $verify = 'g';
            else if ($isTwilioAuthyTwoFactor)
                $verify = 't';
            else
                $verify = false;
            return response()->success([
                'verify' => $verify,
                'token' => $token
            ]);
        } else {
            return response()->error('Invalid User', 401);
        }
    }

    private function processCredentials(UserLoginRequest $request): array
    {
        $credentials = ["password" => $request->password];
        if ($request->has("email"))
            $credentials["email"] = $request->email;
        if ($request->has("username"))
            $credentials["username"] = $request->username;
        return $credentials;
    }
    /**
     * Register API
     * 
     * This endpoint allows you to register a new user.
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function register(UserRegisterRequest $request)
    {
        $credentials = [
            "username" => $request->username,
            "email" => $request->email,
            "password" => $request->password,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "phone_number" => $request->phone_number,
            "country_code" => $request->country_code,
        ];


        $role = $request->role;
        $activate = $request->activate === "true" ? true : false;
        $user = Sentinel::register($credentials, $activate);
        $this->create_authy_api($user);
        $this->attachRole($user, $role);
        return response()->success('User Registered Successfully');
    }

    private function attachRole($user, $role)
    {
        $selectedRole = Sentinel::findRoleBySlug($role);
        $selectedRole->users()->attach($user);
    }

    private function create_authy_api(User $user)
    {
        $authy_api = new AuthyApi(config('authy.app_secret'));
        $user = $authy_api->registerUser(
            $user->email,
            $user->phone_number,
            $user->country_code
        );
        return $user;
    }
}

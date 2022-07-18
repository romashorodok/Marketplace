<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialRequest;
use App\Http\Requests\RegisterCredentialRequest;
use App\Models\User;
use App\Services\AuthenticateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function __construct(
        private AuthenticateService $authenticate,
    )
    {
        $this->middleware('client.credentials')
            ->only(['token']);
    }

    public function login(CredentialRequest $request): Response
    {
        $request->validated();

        $credential = $request->safe(['email', 'password']);

        $result = $this->authenticate->tryAuthenticate($credential);

        if ($result)
            $request->session()->regenerate();

        return $result
            ? response([
                "status" => "OK",
                "token"  => $this->authenticate->getAccessToken()
            ], 200)
            : response(["status" => "BAD"], 401);
    }

    public function logout(Request $request, User|Authenticatable $user = null): Response
    {
        $this->authenticate->logout();
        $request->session()->flush();

        $user?->token()->revoke();

        return response([
            "status" => "OK",
        ], 202);
    }

    public function register(RegisterCredentialRequest $request): Response
    {
        $credentials = $request->validated();
        $password = $credentials['password'];
        $credentials['password'] = Hash::make($credentials['password']);

        try {
            User::create($credentials);

            $authenticated =  $this->authenticate->tryAuthenticate([
                "email" => $credentials['email'],
                "password" => $password
            ]);

            if ($authenticated)
                return response([
                    "status" => "OK",
                    "token" => $this->authenticate->getAccessToken()
                ], 201);
            else
                return response([
                    "status" => "CREATED"
                ], 201);
        } catch (\Exception $e) {
            return response([
                "status" => "BAD",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function token(Request $request): Response
    {

        /* @var User $tokenUser */
        $tokenUser = Auth::guard('api')->user();

        if ($tokenUser->token()->user_id === $request->user()->id)
        {
            return response([
                "status" => "OK"
            ], 200);
        } else
        {
            $this->logout($request, $tokenUser);

            return response([
                "status" => "BAD"
            ], 403);
        }
    }

    public function getUser(Request $request): Response
    {
        return response([
            'user' => $this->authenticate->getUser()
        ], 200);
    }
}

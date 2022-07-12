<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialRequest;
use App\Models\User;
use App\Services\AuthenticateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Authenticatable;


class AuthController extends Controller
{

    public function __construct(
        public AuthenticateService $authenticate,
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

    public function token(Request $request): Response
    {

        /* @var User $tokenUser */
        $tokenUser = \Auth::guard('api')->user();

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

    //TODO: Change it by vue router
    public function loginView() {
        return view('auth.login-vue');
    }
}

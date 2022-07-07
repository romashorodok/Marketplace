<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialRequest;
use App\Services\AuthenticateService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthenticateService $authenticate;

    public function __construct(AuthenticateService $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function login(CredentialRequest $request)
    {
        $request->validated();

        $credential = $request->safe(['email', 'password']);

        $result = $this->authenticate->tryAuthenticate($credential);

        if ($result)
            $request->session()->regenerate();

        return $result
            ? response(["status" => "OK"], 200)
            : response(["status" => "BAD"], 404);
    }

    public function logout(Request $request)
    {
        $this->authenticate->logout();
        $request->session()->flush();

        return response(["status" => "OK"], 202);
    }

    public function getUser(Request $request)
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

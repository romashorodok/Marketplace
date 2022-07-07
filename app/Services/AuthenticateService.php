<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthenticateService
{
    public function getUser()
    {
        return Auth::check() ? Auth::user() : null;
    }

    public function tryAuthenticate($credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function logout()
    {
        Auth::logout();
    }
}

<?php declare(strict_types = 1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateService
{
    public function getUser(): ?User
    {
        return Auth::check() ? Auth::user() : null;
    }

    public function tryAuthenticate($credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}

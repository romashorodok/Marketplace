<?php declare(strict_types = 1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateService
{
    private string $accessToken;

    public function getUser(): ?User
    {
        return Auth::check() ? Auth::user() : null;
    }

    public function tryAuthenticate($credentials): bool
    {
        $result = Auth::attempt($credentials);

        if ($result && Auth::check())
            $this->generateAccessToken();

        return $result;
    }

    public function logout(): void
    {
        Auth::logout();
    }

    private function generateAccessToken(): void
    {
        $user = $this->getUser();

        $this->accessToken = $user->createToken($user->name)->accessToken;
    }

    public function getAccessToken(): ?string
    {
        return Auth::check() ? $this->accessToken : null;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Authenticate\Methods;

use App\Exceptions\AuthenticateException;
use App\Models\User;
use App\Services\Authenticate\Contracts\Authenticate;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Hash;

class ServerAuthenticate implements Authenticate
{

    public function __construct(
        private readonly AuthManager $auth,
        private readonly User        $user
    )
    {
    }

    public function login(array $credentials = []): User
    {
        $authorized = $this->auth->guard()->attempt($credentials);
        /* @var User $user */
        $user = $this->auth->guard()->user();

        if (!$authorized && !$user)
            throw new AuthenticateException('Invalid credentials');

        return $user;
    }

    public function register(array $credentials = []): User
    {
        return $this->user->newQuery()->create([
            ...$credentials,
            'password' => Hash::make($credentials['password'])
        ]);
    }
}

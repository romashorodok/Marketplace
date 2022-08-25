<?php

declare(strict_types=1);

namespace App\Services\Authenticate;

use App\Models\User;
use App\Services\Authenticate\Contracts\AuthenticateMethod;
use App\Services\Authenticate\Contracts\Token;
use App\Services\Authenticate\Tokens\GoogleToken;
use App\Services\Authenticate\Tokens\ServerToken;
use Illuminate\Auth\AuthManager;

class TokenResolver
{
    private string $accessToken = '';

    private ?AuthenticateMethod $method = null;

    private ?User $user = null;

    public function __construct(private readonly AuthManager $auth)
    {
    }

    public function resolve(): ?Token
    {
        $this->user = $this->auth->guard('api')->user() ?? $this->auth->guard()->user();

        return $this->createToken();
    }

    public function setAuthenticateMethod(AuthenticateMethod $method): void
    {
        $this->method = $method;
    }

    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }

    private function createToken(): ?Token
    {
        $payload = [$this->user, $this->accessToken];

        return match ($this->method) {
            AuthenticateMethod::Server => new ServerToken(...$payload),
            AuthenticateMethod::Google => new GoogleToken(...$payload),
            default => null
        };
    }
}

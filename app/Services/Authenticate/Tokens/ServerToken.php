<?php

namespace App\Services\Authenticate\Tokens;

use App\Models\User;
use App\Services\Authenticate\Contracts\Token;
use App\Services\Authenticate\Tokens\Traits\GenerateToken;

class ServerToken implements Token
{
    use GenerateToken;

    public function __construct(
        private readonly ?User $user,
        private string         $token
    )
    {
    }

    public function invalidate(): bool
    {
        return $this->user->token()->delete();
    }

    public function validate(): bool
    {
        return (bool)$this->user?->token();
    }

    public function getAccessToken(): string
    {
        return $this->token;
    }
}

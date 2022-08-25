<?php

declare(strict_types=1);

namespace App\Services\Authenticate\Tokens\Traits;

trait GenerateToken
{
    public function generate(): string
    {
        return $this->token = $this->user->createToken($this->user->id)->accessToken;
    }
}

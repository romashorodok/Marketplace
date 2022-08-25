<?php

namespace App\Services\Authenticate\Contracts;

use App\Exceptions\AuthenticateException;

interface Token
{
    public function generate(): string;

    /**
     * @throws AuthenticateException
     */
    public function invalidate(): bool;

    /**
     * @throws AuthenticateException
     */
    public function validate(): bool;

    public function getAccessToken(): string;
}

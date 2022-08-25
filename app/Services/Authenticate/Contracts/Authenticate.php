<?php

namespace App\Services\Authenticate\Contracts;

use App\Exceptions\AuthenticateException;
use App\Models\User;

interface Authenticate
{
    /**
     * @throws AuthenticateException
     */
    public function login(array $credentials = []): User;

    /**
     * @throws AuthenticateException
     */
    public function register(array $credentials = []): User;
}

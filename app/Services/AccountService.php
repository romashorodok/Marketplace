<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class AccountService
{
    public function __construct(
        private AuthenticateService $authenticate
    ) {}

    public function updatePassword($oldPassword, $newPassword): bool
    {
        $user = $this->authenticate->getUser();

        if (Hash::check($oldPassword, $user->password))
        {
            return $user->update([
                "password" => Hash::make($newPassword),
            ]);
        }

        return false;
    }

    public function updateCredentials($credentials): bool
    {
        return $this->authenticate->getUser()
            ->update($credentials);
    }
}

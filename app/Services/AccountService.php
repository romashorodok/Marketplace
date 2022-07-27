<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CredentialsUpdateException;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    public function __construct(
        private AuthenticateService $authenticate
    ) {}

    /**
     * @throws CredentialsUpdateException
     */
    private function updatePassword(string $oldPassword, string $newPassword): void
    {
        $user = $this->authenticate->getUser();

        if (Hash::check($oldPassword, $user->password))
        {
            $result =  $user->update([
                "password" => Hash::make($newPassword),
            ]);

            if ($result == 0)
                throw new CredentialsUpdateException();

            return;
        }

        throw new CredentialsUpdateException("Invalid credentials");
    }

    /**
     * @throws CredentialsUpdateException
     */
    private function updateName(string $firstName, string $lastName): void
    {
        $result =  $this->authenticate->getUser()->update([
            "firstName" => $firstName,
            "lastName" => $lastName
        ]);

        if ($result == 0)
            throw new CredentialsUpdateException();
    }

    /**
     * @throws CredentialsUpdateException
     */
    public function update(array $credentials): void
    {
        if (isset($credentials['password'], $credentials['newPassword']))
            $this->updatePassword(
                $credentials['password'],
                $credentials['newPassword']
            );

        if (isset($credentials['firstName']) && isset($credentials['lastName']))
            $this->updateName($credentials['firstName'], $credentials['lastName']);
    }
}

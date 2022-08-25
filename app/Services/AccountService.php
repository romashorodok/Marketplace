<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CredentialsUpdateException;
use App\Services\Authenticate\AuthenticateService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;

class AccountService
{
    public function __construct(
        private AuthenticateService $authenticate,
        private MessageBag $messageBag
    ) {}

    /**
     * @throws CredentialsUpdateException
     */
    private function updatePassword(string $oldPassword, string $passwordNew): void
    {
        $user = $this->authenticate->getUser();

        if (Hash::check($oldPassword, $user->password))
        {
            $result =  $user->update([
                "password" => Hash::make($passwordNew),
            ]);

            if (!$result)
                throw new CredentialsUpdateException();

            return;
        }

        $this->messageBag->add('password', "Invalid password");

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

        if (!$result) {
            $this->messageBag->add('firstName', "Cannot update");
            $this->messageBag->add('lastName', "Cannot update");
            throw new CredentialsUpdateException();
        }
    }

    /**
     * @throws CredentialsUpdateException
     */
    public function update(array $credentials): void
    {
        if (isset($credentials['password'], $credentials['passwordNew']))
            $this->updatePassword(
                $credentials['password'],
                $credentials['passwordNew']
            );

        if (isset($credentials['firstName']) && isset($credentials['lastName']))
            $this->updateName($credentials['firstName'], $credentials['lastName']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCredentialRequest;
use App\Services\AccountService;
use App\Services\AuthenticateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function __construct (
        private AuthenticateService $authenticate,
        private AccountService $account
    ) { }

    public function getAccount(Request $request): Response
    {
        return response([
            "account" => $this->authenticate->getUser()
        ], 200);
    }

    public function updateAccount(UpdateCredentialRequest $request): Response
    {
        $credentials = $request->validated();

        if (isset($credentials['password'], $credentials['newPassword']))
        {
            $result = $this->account->updatePassword(
                $credentials['password'],
                $credentials['newPassword']
            );

            if (!$result)
                return response([
                    "message" => "The password incorrect.",
                ], 422);
        }

        if (isset($credentials['firstName']) || isset($credentials['lastName']))
        {
            $result = $this->account->updateCredentials(
                $request->only(['firstName', 'lastName'])
            );

            if (!$result)
                return response([
                    "message" => "Cannot update user credentials"
                ], 422);
        }

        return response([
            "status" => "OK"
        ], 200);
    }
}

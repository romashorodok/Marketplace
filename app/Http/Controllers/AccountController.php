<?php

namespace App\Http\Controllers;

use App\Exceptions\CredentialsUpdateException;
use App\Http\Requests\UpdateCredentialRequest;
use App\Services\AccountService;
use App\Services\AuthenticateService;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function __construct (
        private AuthenticateService $authenticate,
        private AccountService $account
    ) { }

    public function getAccount(): Response
    {
        return response([
            "account" => $this->authenticate->getUser()
        ], 200);
    }

    public function updateAccount(UpdateCredentialRequest $request): Response
    {
        $credentials = $request->validated();

        try {
            $this->account->update($credentials);

        } catch (CredentialsUpdateException $e) {
            return response([
                "message" => $e->getMessage()
            ], 422);
        }

        return response([
            "status" => "OK"
        ], 200);
    }
}

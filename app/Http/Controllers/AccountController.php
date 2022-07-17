<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCredentialRequest;
use App\Models\User;
use App\Services\AuthenticateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct (
        public AuthenticateService $authenticate
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

        $user = $this->authenticate->getUser();

        if (isset($credentials['password'], $credentials['newPassword']))
        {
            if (Hash::check($credentials['password'], $user->password))
            {
                User::whereId($user->id)
                    ->update([
                        ...$request->only(['firstName', 'lastName']),
                        "password" => Hash::make($credentials['newPassword'])
                    ]);

                return response([
                    "account" => $user
                ], 200);
            }

            return response([

            ], 200);
        }

        $user->update($credentials);

        return response([
            "account" => $user,
        ], 200);
    }
}

<?php

namespace App\Auth;

use App\Auth\Forms\CredentialRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(CredentialRequest $request)
    {
        $request->validated();

        $credential = $request->safe(['email', 'password']);

        // Second param may remember it on session
        if (Auth::attempt($credential)) {
            return 'OK';
        }

        return response()->json();
    }
}

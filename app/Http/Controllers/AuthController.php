<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthenticateException;
use App\Http\Requests\MorphRequest;
use App\Http\Requests\CredentialRequest;
use App\Http\Requests\RegisterCredentialRequest;
use App\Services\Authenticate\AuthenticateService;
use App\Services\Authenticate\Contracts\AuthenticateMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\SocialiteManager;

class AuthController extends Controller
{

    public function __construct(
        private readonly AuthenticateService $authenticate,
        private readonly SocialiteManager    $socialite
    )
    {
    }

    public function login(MorphRequest $request): Response|RedirectResponse
    {
        $credentials = [];

        $authMethod = AuthenticateMethod::tryFrom(
            $request->query->get('method', 'server')
        );

        if (AuthenticateMethod::Server === $authMethod) {
            $credentialsReq = $request->transformTo(CredentialRequest::class);

            $credentials = $credentialsReq->validated();
        }

        try {
            $token = $this->authenticate->tryAuthenticate($authMethod, $credentials);

            $request->session()->regenerate();

            if (AuthenticateMethod::Server !== $authMethod)
                return redirect('/');

            return response(['token' => $token->getAccessToken()], 200);
        } catch (AuthenticateException $e) {
            return response(['errors' => $e->getMessage()], 401);
        }
    }

    public function register(RegisterCredentialRequest $request): Response
    {
        $credentials = $request->validated();

        try {
            $token = $this->authenticate->register($credentials);

            $request->session()->regenerate();

            return response(['token' => $token->getAccessToken()], 200);
        } catch (AuthenticateException $e) {
            return response(['errors' => $e->getMessage()], 422);
        }
    }

    public function logout(Request $request): Response
    {
        try {
            $this->authenticate->logout($request);

            return response(["status" => "OK"], 202);
        } catch (AuthenticateException $e) {
            return response(['errors' => $e], 403);
        }
    }

    public function token(Request $request): Response
    {
        try {
            $token = $this->authenticate->getToken($request);

            if (!$token->validate()) throw new AuthenticateException('Invalid token');

            return response(['token' => $token->getAccessToken()]);
        } catch (AuthenticateException $e) {
            return response(['errors' => $e->getMessage()], 403);
        }
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return $this->socialite->driver('google')->redirect();
    }
}

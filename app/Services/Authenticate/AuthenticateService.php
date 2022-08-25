<?php declare(strict_types=1);

namespace App\Services\Authenticate;

use App\Exceptions\AuthenticateException;
use App\Exceptions\EnumException;
use App\Models\User;
use App\Services\Authenticate\Contracts\Authenticate;
use App\Services\Authenticate\Contracts\AuthenticateMethod;
use App\Services\Authenticate\Contracts\Token;
use App\Services\Authenticate\Methods\GoogleAuthenticate;
use App\Services\Authenticate\Methods\ServerAuthenticate;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateService
{
    public function __construct(
        private readonly CookieJar        $cookie,
        private readonly TokenResolver    $token
    )
    {
    }

    public function getUser(): ?User
    {
        return Auth::check() ? Auth::user() : null;
    }

    /**
     * @throws AuthenticateException
     */
    public function tryAuthenticate(AuthenticateMethod $method, array $credentials): Token
    {
        $this->getAuthMethod($method)->login($credentials);

        $token = $this->generateToken($method);

        $this->addCookieToken($method, $token);

        return $token;
    }

    /**
     * @throws AuthenticateException
     */
    public function register(array $credentials): Token
    {
        $authMethod = AuthenticateMethod::Server;

        $auth = $this->getAuthMethod($authMethod);
        $auth->register($credentials);
        $auth->login($credentials);

        $token = $this->generateToken($authMethod);

        $this->addCookieToken($authMethod, $token);

        return $token;
    }

    /**
     * @throws AuthenticateException
     */
    public function getToken(Request $request): Token
    {
        try {
            $this->token->setAuthenticateMethod(
                AuthenticateMethod::from($request->cookies->get('method', ''))
            );
            $this->token->setAccessToken($request->cookies->get('token'));
        } catch (EnumException) {
            throw new AuthenticateException('Cannot resolve token');
        }

        return $this->token->resolve();
    }

    /**
     * @throws AuthenticateException
     */
    public function logout(Request $request): void
    {
        $this->getToken($request)->invalidate();

        Auth::logout();

        $this->deleteCookieToken();

        $request->session()->flush();
    }

    private function deleteCookieToken(): void
    {
        $this->cookie->queue(
            $this->cookie->make('token', null, secure: true, httpOnly: true, sameSite: 'strict'),
        );

        $this->cookie->queue(
            $this->cookie->make('method', null, secure: true, httpOnly: true, sameSite: 'strict')
        );
    }

    private function addCookieToken(AuthenticateMethod $method, Token $token): void
    {
        $this->cookie->queue(
            $this->cookie->make('token', $token->getAccessToken(), 60, secure: true, httpOnly: true, sameSite: 'strict'),
        );

        $this->cookie->queue(
            $this->cookie->make('method', $method->name, 60, secure: true, httpOnly: true, sameSite: 'strict')
        );
    }

    private function getAuthMethod(AuthenticateMethod $method): Authenticate
    {
        return match ($method) {
            AuthenticateMethod::Server => resolve(ServerAuthenticate::class),
            AuthenticateMethod::Google => resolve(GoogleAuthenticate::class)
        };
    }

    private function generateToken(AuthenticateMethod $method): Token
    {
        $this->token->setAuthenticateMethod($method);
        $token = $this->token->resolve();
        $token->generate();
        return $token;
    }
}

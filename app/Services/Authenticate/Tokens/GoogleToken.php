<?php

namespace App\Services\Authenticate\Tokens;

use App\Exceptions\AuthenticateException;
use App\Models\User;
use App\Services\Authenticate\Contracts\Token;
use App\Services\Authenticate\Tokens\Traits\GenerateToken;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\RequestException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GoogleToken implements Token
{
    use GenerateToken;

    private readonly Http $http;

    public function __construct(
        private readonly ?User $user,
        private string         $token
    )
    {
        $this->http = resolve(Http::class);
    }

    /**
     * @throws AuthenticateException
     */
    public function invalidate(): bool
    {
        $result = $this->http->post(config('google.revoke') . $this->getGoogleToken(), null);

        try {
            $result->throw();

            $this->user->token()->delete();

            return true;
        } catch (RequestException $e) {
            throw new AuthenticateException($e->getMessage());
        }
    }

    /**
     * @return bool
     * @throws AuthenticateException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function validate(): bool
    {
        try {
            $result = $this->http->get(config('google.info') . $this->getGoogleToken());

            $result->throw();

            return true;
        } catch (RequestException) {
            throw new AuthenticateException('Token expire or revoked');
        }
    }

    private function getGoogleToken(): string
    {
        $socialUser = $this->user->socialAccounts()->where('social_provider', 'google')->first();

        return $socialUser->social_token;
    }

    public function getAccessToken(): string
    {
        return $this->token;
    }
}

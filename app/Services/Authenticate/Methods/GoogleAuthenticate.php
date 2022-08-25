<?php

declare(strict_types=1);

namespace App\Services\Authenticate\Methods;

use App\Exceptions\AuthenticateException;
use App\Models\Image;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\Authenticate\Contracts\Authenticate;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\User as OauthUser;
use Throwable;

class GoogleAuthenticate implements Authenticate
{

    public function __construct(
        private readonly SocialiteManager $socialite,
        private readonly SocialAccount    $socialAccount,
        private readonly User             $user,
        private readonly Image            $image,
        private readonly AuthManager      $auth,
        private readonly DatabaseManager  $db
    )
    {
    }

    /**
     * @throws AuthenticateException
     */
    public function login(array $credentials = []): User
    {
        $user = $this->getOauthUser();

        $socialAccount = $this->socialAccount->newQuery()
            ->where('social_id', $user->id)
            ->where('social_provider', 'google')
            ->first();

        if ($socialAccount?->user) {
            $this->auth->guard()->login($socialAccount->user);
            $this->refreshGoogleAccessToken($socialAccount, $user->token);
            return $socialAccount->user;
        }

        $credentials = [
            'oauthUser' => $user,
            'token' => $user->token,
        ];

        $user = $this->register($credentials);
        $this->auth->guard()->login($user);
        return $user;
    }

    /**
     * @throws AuthenticateException
     */
    public function register(array $credentials = []): User
    {
        $userOauth = $credentials['oauthUser'];
        $token = $credentials['token'];
        $user = $this->user->newQuery()->where('email', $userOauth['email'])->first();

        try {
            $this->db->beginTransaction();

            if (!$user) {
                $image = $this->image->newQuery()->create(['path' => $userOauth['picture']]);

                $user = $this->user->newQuery()->create([
                    'firstName' => $userOauth['given_name'],
                    'lastName' => $userOauth['family_name'],
                    'email' => $userOauth['email'],
                    'password' => Hash::make(Str::uuid()),
                    'image_id' => $image->id
                ]);
            }

            $socialAccount = $this->socialAccount->newQuery()->where('social_id', $userOauth['id'])->first();

            if (!$socialAccount)
                $this->socialAccount->newQuery()->create([
                    'social_id' => $userOauth['id'],
                    'social_token' => $token,
                    'social_provider' => 'google',
                    'user_id' => $user->id
                ]);

            $this->db->commit();

            return $user;
        } catch (Exception|Throwable $e) {
            throw new AuthenticateException($e->getMessage());
        }
    }

    private function getOauthUser(): OauthUser
    {
        return $this->socialite->driver('google')->user();
    }

    private function refreshGoogleAccessToken(SocialAccount $socialAccount, string $token): void
    {
        $socialAccount->update(['social_token' => $token]);
    }
}

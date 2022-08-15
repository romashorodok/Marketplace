<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Payment\Charge;
use App\Services\Interfaces\PaymentService;
use Stripe\StripeClient;

class StripePaymentService implements PaymentService
{
    public function __construct(
        private StripeClient $client
    ) {}

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function charge(float $amount, string $token): Charge
    {
        $result = $this->client->charges->create([
            'amount' => $amount,
            'currency' => 'uah',
            'source' => $token,
            'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
        ]);

        return new Charge([
            'amount' => $result->amount,
            'id' => $result->id
        ]);
    }

}

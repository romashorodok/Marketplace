<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PaymentException;
use App\Modules\Payment\Charge;
use App\Services\Contracts\PaymentService;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripePaymentService implements PaymentService
{
    public function __construct(
        private StripeClient $client
    ) { }

    /**
     * @throws PaymentException
     */
    public function charge(float $amount, string $token): Charge
    {
        try {
            $result = $this->client->charges->create([
                'amount' => intval($amount * 100),
                'currency' => 'uah',
                'source' => $token,
                'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
            ]);
        } catch (ApiErrorException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return new Charge([
            'amount' => $result->amount,
            'id' => $result->id
        ]);
    }

}

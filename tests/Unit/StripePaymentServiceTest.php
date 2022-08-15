<?php

namespace Tests\Unit;

use App\Services\StripePaymentService;
use Stripe\StripeClient;
use Tests\TestCase;

class StripePaymentServiceTest extends TestCase
{
    private function generateValidPaymentToken(StripeClient $client): string
    {
        $token = $client->tokens->create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 04,
                'exp_year' => now()->addYear()->format('Y'),
                'cvc' => 424
            ]
        ]);
        return $token->id;
    }

    /** @test */
    public function make_successful_charge()
    {
        $client = resolve(StripeClient::class);
        $payment = new StripePaymentService($client);

        dd($this->generateValidPaymentToken($client));

//        $result = $this->payment->charge(50_00, $this->generateValidPaymentToken());

//        dd($result);
    }
}

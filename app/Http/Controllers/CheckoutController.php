<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\PaymentException;
use App\Http\Requests\BillingRequest;
use App\Modules\Payment\Billing;
use App\Services\CheckoutService;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\MessageBag;

class CheckoutController
{
    public function __construct(
        private CheckoutService $checkoutService,
        private MessageBag      $messageBag
    )
    {
    }

    public function checkout(BillingRequest $request): Response
    {
        $billing = new Billing($request->validated());

        try {
            $order = $this->checkoutService->checkout($billing);

            return response(['charge_token' => $order->charge_token], 200);
        } catch (PaymentException) {
            return response(['errors' => $this->messageBag->getMessages()], 422);
        } catch (Exception) {
            return response(['errors' => $this->messageBag->getMessages()], 500);
        }
    }
}

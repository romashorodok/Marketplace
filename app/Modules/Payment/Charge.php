<?php

declare(strict_types=1);

namespace App\Modules\Payment;

class Charge
{
    public function __construct(public array $data) {}

    public function getPaymentId(): int
    {
        return $this->data['id'];
    }

    public function getAmount(): float
    {
        return $this->data['amount'];
    }
}

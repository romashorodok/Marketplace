<?php

declare(strict_types=1);

namespace App\Modules\Payment;

class Charge
{
    public function __construct(private array $data) {}

    public function getPaymentId(): string
    {
        return $this->data['id'];
    }

    public function __toString(): string
    {
        return $this->data['id'];
    }
}

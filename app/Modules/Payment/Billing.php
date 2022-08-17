<?php

declare(strict_types=1);

namespace App\Modules\Payment;

class Billing
{
    public function __construct(private array $data)
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getPaymentToken(): string
    {
        return $this->data['paymentToken'];
    }

    public function getTotalPrice(): float
    {
        return $this->data['total_price'];
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->data['total_price'] = $totalPrice;
    }

    public function setUserId(int $id): void
    {
        $this->data['user_id'] = $id;
    }
}

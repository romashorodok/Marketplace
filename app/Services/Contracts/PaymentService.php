<?php

namespace App\Services\Contracts;

use App\Modules\Payment\Charge;

interface PaymentService
{
    public function charge(float $amount, string $token): Charge;
}

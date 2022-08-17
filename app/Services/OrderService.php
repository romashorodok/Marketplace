<?php

namespace App\Services;

use App\Models\Order;
use App\Modules\Payment\Billing;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderService
{
    public function create(Billing $billing): Order
    {
        return Order::create($billing->toArray());
    }

    public function applyBillingItems(Order $order, HasMany $billingItems): void
    {
        $billingItems->update(['order_id' => $order->id]);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Modules\Payment\Billing;
use App\Repository\OrderRepository;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderService
{
    public function __construct(
        private Order               $order,
        private OrderRepository     $repository,
        private AuthenticateService $authenticate
    ) { }

    public function create(Billing $billing): Order
    {
        return $this->order->newQuery()->create($billing->toArray());
    }

    public function applyBillingItems(Order $order, HasMany $billingItems): void
    {
        $billingItems->update(['order_id' => $order->id]);
    }

    public function getUserOrders(int $page = 1, int $size = 10): array
    {
        $orders = $this->authenticate->getUser()->orders()->getQuery();
        $orders = $this->repository->getByPage($orders, $page, $size);
        $orders = $this->repository->getByLatest($orders);

        return $orders->get()->toArray();
    }

    public function getUserOrdersCount(): int
    {
        $orders = $this->authenticate->getUser()->orders()->getQuery();

        return $orders->count();
    }
}

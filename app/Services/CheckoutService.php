<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Modules\Payment\Billing;
use App\Services\Interfaces\PaymentService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
use Illuminate\Support\MessageBag;
use Exception;

class CheckoutService
{
    public function __construct(
        private CartService     $cart,
        private PaymentService  $payment,
        private OrderService    $order,
        private LogManager      $log,
        private MessageBag      $messageBag,
        private DatabaseManager $db
    ) { }

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function checkout(Billing $billing): Order
    {
        $cart = $this->cart->getCart();

        $billing->setTotalPrice($cart->total_price);
        $billing->setUserId($cart->user_id);

        $order = $this->order->create($billing);
        $charge = null;

        try {
            $this->db->beginTransaction();

            $charge = $this->payment->charge($billing->getTotalPrice(), $billing->getPaymentToken());

            $order->charge_token = $charge->getPaymentId();
            $order->save();

            $this->order->applyBillingItems($order, $cart->billingItems());

            $this->cart->purge();

            $this->db->commit();;
            return $order;
        } catch (PaymentException|Exception $e) {

            $this->log->channel('payment')
                ->critical("checkout error | charge id: $charge | " . $e->getMessage());


            // TODO: If db not available retry saving
            /**
             * Can I send error message to message broker and try save the order again ?
             */

            $this->messageBag->add('card', $e->getMessage());

            $order->delete();
            $this->db->rollBack();

            throw $e;
        }
    }
}

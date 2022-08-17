<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CartItemException;
use App\Models\Cart;
use App\Models\BillingItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;

class CartService
{

    /**
     * @param AuthenticateService $authenticate
     * @param MessageBag $messageBag
     */
    public function __construct(
        private AuthenticateService $authenticate,
        private MessageBag          $messageBag
    ) { }

    /**
     * @return Cart|Model
     */
    public function getCart(): Cart|Model
    {
        $user = $this->authenticate->getUser();

        return $user->cart ?? $user->cart()->create();
    }

    public function purge(): void
    {
        $this->getCart()->delete();
    }

    /**
     * @param array $fields
     * @return Cart|Model
     * @throws CartItemException
     */
    public function createItem(array $fields): Cart|Model
    {
        $cart = $this->getCart();
        $productId = $fields['productId'];

        try {
            if ($this->existItem($cart, $productId))
                throw new CartItemException('Cart item already exist');

            $product = Product::find($productId, 'price');

            $fields = array_merge([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $product->price
            ], $fields);

            BillingItem::create($fields);

            return $cart;
        } catch (CartItemException $e) {
            $this->messageBag->add('cart_item', $e->getMessage());
        }

        throw new CartItemException('Cannot create cart item');
    }

    /**
     * @param int $cartItemId
     * @param array $fields
     * @return Cart|Model
     */
    public function updateItem(int $cartItemId, array $fields): Cart|Model
    {
        $cartItems = $this->getCart()->billingItems();

        $cartItems->where('id', $cartItemId)->update($fields);

        return $this->getCart();
    }

    /**
     * @param int $cartItemId
     * @return Cart|Model
     */
    public function deleteItem(int $cartItemId): Cart|Model
    {
        $cartItems = $this->getCart()->billingItems();

        $cartItems->where('id', $cartItemId)->delete();

        return $this->getCart();
    }

    /**
     * @return Cart|Model
     */
    public function deleteItems(): Cart|Model
    {
        $this->getCart()->billingItems()->delete();

        return $this->getCart();
    }

    private function existItem(Cart $cart, int $productId): bool
    {
        return $cart->billingItems()->where('product_id', $productId)->exists();
    }
}

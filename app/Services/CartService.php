<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CartItemException;
use App\Exceptions\ProductException;
use App\Models\Cart;
use App\Models\CartItem;
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
        private MessageBag $messageBag
    ) { }

    /**
     * @return Cart|Model
     */
    public function getCart(): Cart|Model
    {
        $user = $this->authenticate->getUser();

        return $user->cart ?? $user->cart()->create();
    }

    /**
     * @param array $fields
     * @return Cart|Model
     * @throws CartItemException
     */
    public function createItem(array $fields): Cart|Model
    {
        $cart = $this->getCart();

        $fields = array_merge(['cart_id' => $cart->id], $fields);

        try {
            CartItem::createWithProduct($fields, $fields['productId']);

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
        $cartItems = $this->getCart()->cartItems();

        $cartItems->where('id', $cartItemId)->update($fields);

        return $this->getCart();
    }

    /**
     * @param int $cartItemId
     * @return Cart|Model
     */
    public function deleteItem(int $cartItemId): Cart|Model
    {
        $cartItems = $this->getCart()->cartItems();

        $cartItems->where('id', $cartItemId)->delete();

        return $this->getCart();
    }

    /**
     * @return Cart|Model
     */
    public function deleteItems(): Cart|Model
    {
        $this->getCart()->cartItems()->delete();

        return $this->getCart();
    }
}

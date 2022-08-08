<?php

namespace App\Http\Controllers;

use App\Exceptions\CartItemException;
use App\Http\Requests\CartItemRequest;
use App\Services\CartService;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;

class CartController extends Controller
{
    /**
     * @param CartService $cart
     * @param MessageBag $messageBag
     */
    public function __construct(
        private CartService $cart,
        private MessageBag $messageBag
    ) { }

    /**
     * @return Response
     */
    public function getCart(): Response
    {
        return response(["cart" => $this->cart->getCart()], 200);
    }

    /**
     * @param int $cartItemId
     * @param CartItemRequest $request
     * @return Response
     */
    public function updateCartItem(int $cartItemId, CartItemRequest $request): Response
    {
        $freshItemFields = $request->validated();
        $cart = $this->cart->updateItem($cartItemId, $freshItemFields);

        return response(["cart" => $cart->refresh()], 200);
    }

    /**
     * @param int $cartItemId
     * @return Response
     */
    public function deleteCartItem(int $cartItemId): Response
    {
        $cart = $this->cart->deleteItem($cartItemId);

        return response(["cart" => $cart->refresh()], 200);
    }

    /**
     * @return Response
     */
    public function deleteCartItems(): Response
    {
        $cart = $this->cart->deleteItems();

        return response(["cart" => $cart->refresh()], 200);
    }

    /**
     * @param CartItemRequest $request
     * @return Response
     */
    public function addToCart(CartItemRequest $request): Response
    {
        $fields = $request->validated();

        try {
            $cart = $this->cart->createItem($fields);

            return response(["cart" => $cart->refresh()], 201);
        } catch (CartItemException) {
            return response(["errors" => $this->messageBag->getMessages()], 422);
        }
    }
}

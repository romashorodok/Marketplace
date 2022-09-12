<?php

namespace App\Http\Controllers;

use App\Exceptions\CredentialsUpdateException;
use App\Http\Requests\UpdateCredentialRequest;
use App\Services\AccountService;
use App\Services\Authenticate\AuthenticateService;
use App\Services\OrderService;
use App\Services\PaginateService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;

class AccountController extends Controller
{
    public function __construct(
        private readonly AuthenticateService $authenticate,
        private readonly AccountService      $account,
        private readonly OrderService        $order,
        private readonly ProductService      $product,
        private readonly PaginateService     $paginate,
        private readonly MessageBag          $messageBag
    )
    {
    }

    public function getAccount(): Response
    {
        return response([
            "account" => $this->authenticate->getUser()
        ], 200);
    }

    public function updateAccount(UpdateCredentialRequest $request): Response
    {
        $credentials = $request->validated();

        try {
            $this->account->update($credentials);

        } catch (CredentialsUpdateException $e) {
            return response([
                "errors" => $this->messageBag->getMessages(),
                "message" => $e->getMessage()
            ], 422);
        }

        return response([
            "status" => "OK"
        ], 200);
    }

    /**
     * @throws \App\Exceptions\PaginationException
     */
    public function getOrders(Request $request): Response
    {
        $requestQueries = $request->query->all();

        $page = intval($requestQueries['page'] ?? 1);
        $size = intval($requestQueries['size'] ?? 10);

        try {
            $ordersCount = $this->order->getUserOrdersCount();
            $orders = $this->order->getUserOrders($page, $size);
            $orders = $this->paginate->getPagination(
                $orders,
                $page,
                $size,
                $ordersCount
            );

            return response(['orders' => $orders], 200);
        } catch (Exception $e) {
            return response(['errors' => $e->getMessage()], 500);
        }
    }

    public function getProducts(Request $request): Response
    {
        $page = $request->query('page', 1);
        $size = $request->query('size', 50);

        try {
            $products = $this->paginate->getPagination(
                $this->product->getUserProducts($page, $size),
                $page,
                $size,
                $this->product->getUserProductsCount()
            );

            return response(['products' => $products], 200);
        } catch (Exception $e) {
            return response(['errors' => $e->getMessage()], 500);
        }
    }

    public function createProduct(Request $request): Response
    {
        $product = $this->product->createUserProduct($request->all());

        return response(['product' => $product], 200);
    }

    public function updateProduct(Request $request): Response
    {
        $this->product->updateUserProduct($request->all());

        return response(['product' => ''], 201);
    }

    public function deleteProduct(int $id): Response
    {
        $this->product->deleteUserProduct($id);

        return response(['product' => ''], 201);
    }
}

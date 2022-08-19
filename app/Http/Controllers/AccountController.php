<?php

namespace App\Http\Controllers;

use App\Exceptions\CredentialsUpdateException;
use App\Http\Requests\UpdateCredentialRequest;
use App\Services\AccountService;
use App\Services\AuthenticateService;
use App\Services\OrderService;
use App\Services\PaginateService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;

class AccountController extends Controller
{
    public function __construct(
        private AuthenticateService $authenticate,
        private AccountService      $account,
        private OrderService        $order,
        private PaginateService     $paginate,
        private MessageBag          $messageBag
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
}

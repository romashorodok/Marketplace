<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\PaginationException;
use App\Services\PaginateService;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService  $product,
        private PaginateService $paginate,
    )
    {
    }

    public function getProduct(Request $request): Response
    {
        $requestQueries = $request->query->all();

        $page = intval($requestQueries['page'] ?? 1);
        $size = intval($requestQueries['size'] ?? 50);
        $categories = $requestQueries['categories'] ?? [];
        $categories = !empty($categories) ? explode(',', $categories) : [];

        $productsCount = $this->product->getCountByCategory($categories);
        $products = $this->product->getProducts($page, $size, $categories);

        try {
            $products = $this->paginate->getPagination(
                $products->get()->toArray(),
                $page,
                $size,
                $productsCount
            );

            return response(["products" => $products], 200);
        } catch (PaginationException $e) {

            return response(["errors" => [
                "pagination" => $e->getMessage()
            ]], 422);
        }
    }
}

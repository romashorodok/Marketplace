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
    ) { }

    public function getProduct(Request $request): Response
    {
        $name = $request->get('name', '');
        $page = intval($request->get('page', 1));
        $size = intval($request->get('size', 50));
        $categories = $request->get('categories', []);
        $categories = !empty($categories) ? explode(',', $categories) : [];

        $products = $this->product->getProducts($page, $size, $name, $categories);

        try {
            $products = $this->paginate->getPagination(
                $products->get()->toArray(),
                $page,
                $size,
                $this->product->getCount($name, $categories)
            );

            return response(["products" => $products], 200);
        } catch (PaginationException $e) {

            return response(["errors" => [
                "pagination" => $e->getMessage()
            ]], 422);
        }
    }

    public function getProductById(int $id): Response
    {
        $product = $this->product->getProductById($id);

        return response(["product" => $product], 200);
    }
}

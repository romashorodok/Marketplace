<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $product
    ) {}

    public function getProduct(Request $request): Response
    {
        $paginationSize = $request->get('size') ?? 20;

        $categories = explode(',', $request->get('categories'));

        if (!empty($categories[0])) {
            return response(["products" =>
                $this->product->filterByCategories($categories)
                    ->with('category')
                    ->paginate()
                    ->appends("size", $paginationSize)
                    ->appends('categories', $request->get('categories'))
            ], 200);
        }

        return response([
            "products" => Product::inRandomOrder('1')
                ->orderByDesc('id')
                ->paginate($paginationSize)
                ->appends("size", $paginationSize),
        ], 200);
    }
}

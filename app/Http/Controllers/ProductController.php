<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct(Request $request): Response
    {
        $size = $request->query('size') ?? 20;

        return response([
            "products" => Product::inRandomOrder()
                ->orderByDesc('id')
                ->paginate($size)
                ->appends("size", $size)
        ], 200);
    }
}

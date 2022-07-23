<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProduct(Request $request): Response
    {
        $paginationSize = $request->get('size') ?? 50;

        $categories = explode(',', $request->get('categories'));

        if (!empty($categories[0]))
        {

            $test = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'categories.*');


            foreach ($categories as $category)
            {
                $test->orWhere('categories.name', 'like', "%{$category}%");
            }



            return response([
                "products" => $test->orderByDesc('price', 'DESC')
                    ->paginate($paginationSize)
                    ->appends("size", $paginationSize)
            ], 200);
        }

        return response([
            "products" => Product::inRandomOrder()
                ->orderByDesc('id')
                ->paginate($paginationSize)
                ->appends("size", $paginationSize),
        ], 200);
    }
}

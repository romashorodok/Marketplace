<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories(): Response
    {
        return response([
            "categories" => Category::groupBy('name')
                ->selectRaw('name, count(*) as count')
                ->get()
        ], 200);
    }
}

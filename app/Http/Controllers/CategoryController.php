<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories(): Response
    {
        $categories = Category::groupBy('name')
            ->selectRaw('name, count(*) as count')
            ->get();

        $categories = collect($categories)->map(
            fn($category) => Category::whereName($category->name)->first()
        );

        return response(["categories" => $categories], 200);
    }
}

<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    public function getProductByCategories(array $categories): Builder|Product
    {
        /**
         * Is it optimal? Or when is ORM paginate the query it will be optimized ?
         */
        return Product::whereHas('category', function (Builder $query) use ($categories) {
            $query->whereIn('name', $categories);
        });
    }
}

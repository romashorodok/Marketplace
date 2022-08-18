<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends Repository
{
    public function getByCategories(Builder $query, array $categories): Builder|Product
    {
        return $query->orWhereHas('category', function (Builder $query) use ($categories) {
            $query->whereIn('name', $categories);
        });
    }
}

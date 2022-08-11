<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    public function getByPage(Builder $query, int $page, int $size): Builder|Product
    {
        return $query->offset(($page - 1) * $size)->limit($size);
    }

    public function getByCategories(Builder $query, array $categories): Builder|Product
    {
        return $query->orWhereHas('category', function (Builder $query) use ($categories) {
            $query->whereIn('name', $categories);
        });
    }
}

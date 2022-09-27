<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Product;
use App\Repository\Traits\Pageable;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    use Pageable;

    public function getByCategories(Builder $query, array $categories): Builder|Product
    {
        return $query->orWhereHas('category', function (Builder $query) use ($categories) {
            $query->whereIn('name', $categories);
        });
    }

    public function getByName(Builder $query, string $name): Builder|Product
    {
        return $query->where('name', 'like', $name . "%");
    }
}

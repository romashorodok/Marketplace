<?php

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function __construct (
        private ProductRepository $repository
    ) { }

    public function filterByCategories(array $categories): Builder|Product
    {
        return $this->repository->getProductByCategories($categories);
    }
}

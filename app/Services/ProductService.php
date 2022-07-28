<?php

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct (
        private ProductRepository $repository
    ) { }

    public function filterByCategories(array $categories, string|int $size): LengthAwarePaginator
    {
        $query = $this->repository->getProductByCategories($categories);

        return $this->repository->paginateWithImageAndCategory($query, $size);
    }

    public function getRandomProducts(string|int $size)
    {
        $query = Product::query();

        return $this->repository->paginateWithImageAndCategory($query, $size);
    }
}

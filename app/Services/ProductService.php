<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function __construct(
        private ProductRepository $repository,
        private Product           $product,
    ) { }

    public function getProducts(int $page = 1, int $size = 50, array $categories = []): Builder|Product
    {
        $products = $this->product->newQuery();
        $products = $this->repository->getByPage($products, $page, $size);

        if ($categories)
            $products = $this->repository->getByCategories($products, $categories);

        return $products;
    }

    public function getCountByCategory(array $category): int
    {
        if ($category)
            return $this->repository->getByCategories($this->product->newQuery(), $category)->count();

        return $this->product->count();
    }
}

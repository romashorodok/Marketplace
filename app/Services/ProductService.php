<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;
use App\Services\Authenticate\AuthenticateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function __construct(
        private readonly ProductRepository   $repository,
        private readonly Product             $product,
        private readonly AuthenticateService $authenticate,
        private readonly ImageService        $image
    )
    {
    }

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

    public function getProductById(int $id): Collection|Product
    {
        return Product::with('image')->find($id);
    }


    public function getUserProducts(int $page = 1, int $size = 50): array
    {
        $products = $this->authenticate->getUser()->products()->getQuery();

        return $this->repository->getByPage($products, $page, $size)->get()->toArray();
    }

    public function getUserProductsCount(): int
    {
        return $this->authenticate->getUser()->products()->count();
    }

    public function createUserProduct(array $data): Product
    {
        $image = $this->image->create($data['image']);

        return $this->product->newQuery()->create([
            'vendor_id' => $this->authenticate->getUser()->id,
            'image_id' => $image->id,
            //TODO: support count
            'count' => 1,
            ...$data
        ]);
    }

    public function updateUserProduct(array $data): bool
    {
        $product = $this->product->newQuery()->find($data['id']);

        if (is_object($data['image']) && get_class($data['image']) === UploadedFile::class)
            $this->image->update($product->image_id, $data['image']);

        return $product->update($data);
    }
}

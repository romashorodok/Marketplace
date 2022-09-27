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
        private readonly ImageService        $image,
    )
    {
    }

    public function getProducts(int $page = 1, int $size = 50, ?string $name = '', array $categories = []): Builder|Product
    {
        $products = $this->product->newQuery();
        $products = $this->repository->getByPage($products, $page, $size);

        if ($categories)
            $products = $this->repository->getByCategories($products, $categories);

        if ($name)
            $products = $this->repository->getByName($products, $name);

        return $products;
    }

    public function getCount(?string $name, ?array $category): int
    {
        $product = $this->product->newQuery();

        if ($category)
            $product = $this->repository->getByCategories($product, $category);

        if($name)
            $product = $this->repository->getByName($product, $name);

        return $product->count();
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

    public function deleteUserProduct(int $id): bool
    {
        return (bool)$this->authenticate->getUser()->products()->find($id)->update([
            'vendor_id' => null
        ]);

        // What about force delete ?

//        $product = $this->authenticate->getUser()->products()->find($id);
//        $this->image->delete($product?->image);
//        return (bool)$product->delete();
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Traits\Pageable;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository
{
    use Pageable;

    public function getByLatest(Builder $query)
    {
        return $query->latest('created_at');
    }
}

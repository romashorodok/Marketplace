<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends Repository
{
    public function getByLatest(Builder $query)
    {
        return $query->latest('created_at');
    }
}

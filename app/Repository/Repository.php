<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

abstract class Repository
{
    public function getByPage(Builder $query, int $page, int $size): Builder
    {
        return $query->offset(($page - 1) * $size)->limit($size);
    }
}

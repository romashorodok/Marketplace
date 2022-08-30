<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Pageable
{
    public function getByPage(Builder $query, int $page, int $size): Builder
    {
        return $query->offset(($page - 1) * $size)->limit($size);
    }
}

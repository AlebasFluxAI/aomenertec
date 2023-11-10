<?php

namespace App\Models\Traits;

use App\Scope\PaginationScope;

trait PaginatorTrait
{
    public function scopePagination($query)
    {
        return $query->paginate(PaginationScope::PAGE_SIZE);
    }
}

<?php

namespace App\Models\Traits;

use App\Models\V1\Change;
use App\Scope\PaginationScope;

trait PaginatorTrait
{
    public function scopePagination()
    {
        return parent::paginate(PaginationScope::PAGE_SIZE);
    }
}

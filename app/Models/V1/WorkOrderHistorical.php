<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderHistorical extends Model
{
    use HasFactory;
    use PaginatorTrait;
}

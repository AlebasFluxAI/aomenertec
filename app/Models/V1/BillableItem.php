<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillableItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;

    protected $fillable = [
        "name",
        "description",
        "code",
        "tax_id"
    ];

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }
}

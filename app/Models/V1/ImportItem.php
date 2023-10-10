<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "import_id",
        "error",
        "status",
        "importable_type",
        "importable_id",
        "item_index"
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('item_index_order', function (Builder $builder) {
            $builder->orderBy('item_index', "asc");
        });
    }

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function importable()
    {
        return $this->morphTo();
    }

    public function clientRow()
    {
        return $this->importable->id . " " . $this->importable->alias;
    }

}

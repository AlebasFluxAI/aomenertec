<?php

namespace App\Models\V1;

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
    ];

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

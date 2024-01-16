<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiTree extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "title",
        "content",
        "enabled",
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(WikiTree::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(WikiTree::class, "parent_id");
    }
}

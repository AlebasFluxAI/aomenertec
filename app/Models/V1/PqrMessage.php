<?php

namespace App\Models\V1;

use App\Models\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrMessage extends Model
{
    use ImageableTrait;
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        "message"
    ];

    public function image()
    {
        return $this->morphOne(Image::class, "imageable");
    }
}

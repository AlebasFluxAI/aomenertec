<?php

namespace App\Models\Model\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmware extends Model
{
    use HasFactory;
    use AuditableTrait;
    use ImageableTrait;
    use PaginatorTrait;

    protected $table = 'firmware';
    protected $fillable = [
        'name',
        'version',
        'description'
    ];

    public function evidences()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences");
    }
    public function evidence()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences")->first();
    }


}

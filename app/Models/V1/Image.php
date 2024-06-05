<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    use PaginatorTrait;


    public const URL_BASE = 'images/';

    protected $fillable = ['name', 'title', 'alt', 'type', "description"];
    private $dataImage;

    public static function validateImageFile($image): bool
    {
        $name = explode(".", $image->getAttributes()["name"]);
        if (in_array(end($name), self::fileExtensions())) {
            return false;
        }
        return true;

    }

    public static function fileExtensions()
    {
        return ["pdf", "docx", "bin"];
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'ASC')
                ->orderBy('name', 'ASC');
        });
    }

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImageableUuidAttribute()
    {
        if ($this->imageable) {
            return $this->imageable->uuid;
        }

        return null;
    }

    public function getDataImage()
    {
        return $this->dataImage;
    }

    public function setDataImage($dataImage)
    {
        $this->dataImage = $dataImage;
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = preg_replace('/ |\\|\//', '_', $value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->escape_space($value);
    }

    public function escape_space($value)
    {
        return preg_replace('/ |\\|\//', '_', $value);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = $this->escape_space($value);
    }
}

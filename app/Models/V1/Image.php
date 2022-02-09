<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    public const URL_BASE = 'images/';

    protected $fillable = ['name', 'title', 'alt', 'type'];

    private $dataImage;

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

    public function setDataImage($dataImage)
    {
        $this->dataImage = $dataImage;
    }

    public function getDataImage()
    {
        return $this->dataImage;
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = preg_replace('/ |\\|\//', '_', $value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->escape_space($value);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = $this->escape_space($value);
    }

    public function escape_space($value)
    {
        return preg_replace('/ |\\|\//', '_', $value);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'ASC')
                ->orderBy('name', 'ASC')
            ;
        });
    }
}

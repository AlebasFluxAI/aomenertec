<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use Illuminate\Support\Facades\Request;

trait ImageableManyTrait
{
    public function buildManyImage(array $image_names, $images)
    {
        foreach ($image_names as $type) {
            $image = new Image();
            $image->id = time() . mt_rand(0, 9999999);
            $image->type = $type;
            $image->name = 'no_found.jpg';
            $image->file_name = 'no_found.jpg';
            $no_found_path = '/img/no_found.jpg';
            $image->size = '40400';
            $image->mime_type = 'image/jpeg';
            $image->path = $no_found_path;
            $image->url = $no_found_path;
            $this->{$type}()->saveMany([$image]);

            if (in_array($type, array_keys(Request::all()))) {
                $image = Request::file($type);
                $this->{$type}->setDataImage($image);
                $this->{$type}->name = $image->getClientOriginalName();
                $this->{$type}->update();
            }
        }
    }
}

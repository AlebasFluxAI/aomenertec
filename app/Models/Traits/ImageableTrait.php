<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;


trait ImageableTrait
{
    public function saveImageOnModelWithMorphMany($file, $image_relation, $description = null)
    {
        $image = new Image();
        $image->id = time() . mt_rand(0, 9999999);
        $image->type = $image_relation;
        $image->name = 'no_found.png';
        $image->file_name = 'no_found.png';
        $no_found_path = '/img/no_found.png';
        $image->size = '40400';
        $image->mime_type = 'image/png';
        $image->path = $no_found_path;
        $image->url = $no_found_path;
        $image->description = $description;
        $imageSaved = $this->{$image_relation}()->save($image);

        $imageSaved->setDataImage($file);
        $imageSaved->name = $file->getClientOriginalName();
        $imageSaved->update();
    }

    public function buildOneImageFromFile($image_name, $imageInput)
    {
        $image = new Image();
        $image->id = time() . mt_rand(0, 9999999);
        $image->type = $image_name;
        $image->name = 'no_found.png';
        $image->file_name = 'no_found.png';
        $no_found_path = '/img/no_found.png';
        $image->size = '40400';
        $image->mime_type = 'image/png';
        $image->path = $no_found_path;
        $image->url = $no_found_path;
        $this->{$image_name}()->save($image);

        $image = $imageInput;
        if (!$this->{$image_name}) {
            return;
        }
        $this->{$image_name}->setDataImage($image);
        $this->{$image_name}->name = $image->getClientOriginalName();
        $this->{$image_name}->update();
    }

    public function buildOneImage(array $image_names)
    {
        foreach ($image_names as $type) {
            $image = new Image();
            $image->id = time() . mt_rand(0, 9999999);
            $image->type = $type;
            $image->name = 'no_found.png';
            $image->file_name = 'no_found.png';
            $no_found_path = '/img/no_found.png';
            $image->size = '40400';
            $image->mime_type = 'image/png';
            $image->path = $no_found_path;
            $image->url = $no_found_path;
            $this->{$type}()->save($image);

            if (in_array($type, array_keys(Request::all()))) {
                $image = Request::file($type);
                $this->{$type}->setDataImage($image);
                $this->{$type}->name = $image->getClientOriginalName();
                $this->{$type}->update();
            }
        }
    }

    public function buildManyImage(array $image_names)
    {
        foreach ($image_names as $type) {
            $image = new Image();
            $image->id = time() . mt_rand(0, 9999999);
            $image->type = $type;
            $image->name = 'no_found.png';
            $image->file_name = 'no_found.png';
            $no_found_path = '/img/no_found.png';
            $image->size = '40400';
            $image->mime_type = 'image/png';
            $image->path = $no_found_path;
            $image->url = $no_found_path;
            $this->{$type}()->save($image);

            if (in_array($type, array_keys(Request::all()))) {
                $image = Request::file($type);
                $this->{$type}->setDataImage($image);
                $this->{$type}->name = $image->getClientOriginalName();
                $this->{$type}->update();
            }
        }
    }

    public function downloadFileFromS3($filePath)
    {
        $disk = Storage::disk('s3');
        if ($disk->exists($filePath)) {
            $tempFilePath = storage_path('app/temp-files/' . basename($filePath));
            $fileContent = $disk->get($filePath);
            file_put_contents($tempFilePath, $fileContent);
            return $tempFilePath;
        }

        throw new \Exception("El archivo no existe en S3.");
    }
}

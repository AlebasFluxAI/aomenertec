<?php

namespace App\Observers\Image;

use App\Jobs\Api\V1\Image\SendImageToMsJob;
use App\Models\V1\Image;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image as ImageResize;
use Intervention\Image\ImageManagerStatic as ImageManager;

class ImageObserver
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->image_key = $request->image_key ? $request->image_key : 'image';
    }

    public function creating(Image $image)
    {
        $images = $image->getDataImage() ? $image->getDataImage() : $this->request->file($this->image_key);
        $image->id = time() . mt_rand(0, 9999999);

        if (!$images) {
            if (!$image->name) {
                $image->name = 'No existe';
            }

            $image->file_name = 'no_found.png';
            $image->size = '40400';
            $image->mime_type = 'image/jpeg';
            $image->path = '/img/no_found.png';
            $image->url = url('/img/no_found.png');
            $image->alt = 'No existe';
            $image->title = 'No existe';

            return;
        }

        $timestap = Carbon::now()->timestamp . '_';

        if (!is_string($images)) {
            $name = $timestap . preg_replace('/ |\\|\//', '_', $images->getClientOriginalName());
            $image->file_name = $images->getClientOriginalName();

            if (!$image->name) {
                $image->name = $name;
            }

            if (!$image->alt) {
                $image->alt = $name;
            }

            if (!$image->title) {
                $image->title = $name;
            }

            $image->size = $images->getMaxFilesize();
            $image->mime_type = $images->getClientMimeType();
            $path = Image::URL_BASE . $image->id;
            $image->path = $path . '/' . $name;
            $image->url = Storage::url($image->path);

            Storage::disk('public')->putFileAs($path, new File($images), $name);
            $image->link = $this->request->input('link');
        } else {
            $name = $timestap . $image->name;

            if (!$image->name) {
                $image->name = $name;
            }

            if (!$image->alt) {
                $image->alt = $name;
            }

            if (!$image->title) {
                $image->title = $name;
            }

            $path = Image::URL_BASE . $image->id;
            $image->path = $path . '/' . $image->name;
            $image->url = Storage::url($image->path);
            Storage::disk('public')->put($image->path, $images);
        }
    }


    public function created(Image $image)
    {
        if ('pdf' == last(explode('.', $image->path))) {
            return;
        }

        if (Storage::disk('public')->exists($image->path)) {
            if (Image::validateImageFile($image)) {
                $this->optimizeImageIntervention($image);
            }
            $this->storeS3($image);
        }
    }


    private function optimizeImageIntervention(Image $image)
    {
        $filetype = explode('.', $image->path);
        $size = count($filetype);
        $filetype = $filetype[$size - 1];
        $encoded = ImageManager::make(storage_path('app/public') . '/' . $image->path)
            ->encode($filetype, config('image.quality'));

        if (!$encoded) {
            throw new Exception('Error Optimizing Image');
        }
        Storage::disk('public')->put($image->path, $encoded->stream());
    }

    private function storeS3(Image $image)
    {
        $imageData = explode('/', $image->path);
        $publicUrl = config('image.publicUrl');

        Storage::disk('s3')->put(
            $image->path,
            Storage::disk('public')->get($image->path),
            'public'
        );
        Storage::disk('public')->delete($image->path);
        $getImage = Storage::disk('s3')->get($image->path);

        if ((bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $getImage)) {
            return;
        }

        $image->url = $publicUrl . $image->path;
        $image->save();
    }

    public function updating(Image $image)
    {
        $images = $image->getDataImage() ? $image->getDataImage() : $this->request->file($this->image_key);

        if (!$images) {
            return;
        }
        $timestap = Carbon::now()->timestamp . '_';
        $name = $timestap . preg_replace('/ |\\|\//', '_', $images->getClientOriginalName());
        //New sizes
        $imageData = explode('/', $image->path);

        Storage::disk('s3')->deleteDirectory(Image::URL_BASE . $imageData[1]);

        $image->name = $name;
        $image->file_name = $name;
        $path = Image::URL_BASE . $image->id;
        $image->path = $path . '/' . $name;
        $image->size = $images->getMaxFilesize();
        $image->mime_type = $images->getClientMimeType();
        $image->url = Storage::url($image->path);
        $image->title = $name;
        $image->alt = $name;
        Storage::disk('public')->putFileAs($path, $images, $name);
        if (Image::validateImageFile($image)) {
            $this->optimizeImageIntervention($image);
        }
        $this->updateS3($image);
    }

    private function updateS3(Image $image)
    {
        $imageData = explode('/', $image->path);
        $publicUrl = config('image.publicUrl');

        Storage::disk('s3')->put(
            $image->path,
            Storage::disk('public')->get($image->path),
            'public'
        );
        Storage::disk('public')->delete($image->path);
        $getImage = Storage::disk('s3')->get($image->path);


        $image->url = $publicUrl . $image->path;
    }

    /**
     * Determines if the given image is a resizeable class
     * Return true in case that it is implemented in the config image.S3ResizeClass
     * or false in otherwise.
     *
     * @param mixed $image
     */
}

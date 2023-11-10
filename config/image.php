<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',
    // public aws url are in the format: <bucket>.s3.<region>.amazonaws.com/<file_path>
    'publicUrl' => 'https://' . env('AWS_BUCKET', '<bucket>') . '.s3.' . env('AWS_DEFAULT_REGION', '<region>') . '.amazonaws.com/',

    // Defines the optimize image quality, just apply to the intervention encoder
    'quality' => 90,
];

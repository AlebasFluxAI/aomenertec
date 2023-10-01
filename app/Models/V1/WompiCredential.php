<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WompiCredential extends Model
{
    use HasFactory;

    protected $fillable = ["public_key", "secret_key"];

    public function credentiable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Role;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id"
    ];

    public static function getRole()
    {
        return "administrator";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

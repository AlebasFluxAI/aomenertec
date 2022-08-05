<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    use HasFactory;

    const CHANGE_TYPE_CREATED = "created";
    const CHANGE_TYPE_UPDATED = "updated";
    const CHANGE_TYPE_DELETED = "deleted";

    protected $fillable = [
        "before",
        "after",
        "delta",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->belongsTo($this->model, "model_id");
    }
}

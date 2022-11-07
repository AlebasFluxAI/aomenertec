<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    use HasFactory;
    use PaginatorTrait;


    public const CHANGE_TYPE_CREATED = "created";
    public const CHANGE_TYPE_UPDATED = "updated";
    public const CHANGE_TYPE_DELETED = "deleted";

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

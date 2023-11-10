<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrSolution extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    public const ACCEPTED_TYPE_USER = 'user';
    public const ACCEPTED_TYPE_NETWORK_OPERATOR = 'network_operator';

    protected $fillable = [
        'pqr_id',
        'status',
        'solution',
        'accepted_type',
        'accepted_at',
        'pending_at',
        'rejected_at',
        'accepted_by',
    ];


    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

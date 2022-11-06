<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminClientType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;

    protected $fillable = [
        'admin_id',
        'client_type_id'
    ];

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

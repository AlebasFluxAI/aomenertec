<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSupport extends Model
{
    use HasFactory;
    use AuditableTrait;

    protected $fillable = [
        'client_id',
        'support_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'support_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

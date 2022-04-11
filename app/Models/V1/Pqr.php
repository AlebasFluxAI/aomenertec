<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pqr extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'detail',
        'equipment_id',
        'pqr_type_id',
        'network_operator_id',
        'user_id',
        'client_id',
        'support_id',
        'status'
    ];

    public const STATUS_CREATED = 'created';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public function pqrState()
    {
        return $this->belongsTo(PqrState::class);
    }
    public function pqrType()
    {
        return $this->belongsTo(PqrType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
    public function support()
    {
        return $this->belongsTo(Support::class);
    }
    public function pqrPosts()
    {
        return $this->hasMany(PqrMessage::class);
    }
}
